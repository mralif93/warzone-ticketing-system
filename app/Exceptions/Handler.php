<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions for debugging
            Log::error('Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    }

    /**
     * Render an exception into an HTTP response.
     * 
     * In production, show user-friendly error pages instead of detailed stack traces.
     */
    public function render($request, Throwable $e)
    {
        // Always log the exception for debugging
        Log::error('Exception rendered', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // In production, show user-friendly error pages
        if (!config('app.debug')) {
            return $this->renderUserFriendlyError($request, $e);
        }

        // In debug mode, show detailed error pages
        return parent::render($request, $e);
    }

    /**
     * Render user-friendly error pages for production
     */
    protected function renderUserFriendlyError(Request $request, Throwable $e)
    {
        $statusCode = $this->getStatusCode($e);
        
        // Check if custom error view exists
        if (view()->exists("errors.{$statusCode}")) {
            return response()->view("errors.{$statusCode}", [
                'message' => $this->getUserFriendlyMessage($e, $statusCode),
            ], $statusCode);
        }

        // Fallback to generic error page
        return response()->view('errors.generic', [
            'message' => $this->getUserFriendlyMessage($e, $statusCode),
            'statusCode' => $statusCode,
        ], $statusCode);
    }

    /**
     * Get HTTP status code from exception
     */
    protected function getStatusCode(Throwable $e): int
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        if (method_exists($e, 'getCode') && $e->getCode() >= 400 && $e->getCode() < 600) {
            return $e->getCode();
        }

        // Default to 500 for unhandled exceptions
        return 500;
    }

    /**
     * Get user-friendly error message
     */
    protected function getUserFriendlyMessage(Throwable $e, int $statusCode): string
    {
        // Database errors
        if ($e instanceof \Illuminate\Database\QueryException) {
            return 'We encountered a database error. Please try again in a few moments. If the problem persists, please contact support.';
        }

        // Validation errors
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return 'Please check your input and try again.';
        }

        // Authentication errors
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return 'Please log in to access this page.';
        }

        // Authorization errors
        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return 'You do not have permission to access this resource.';
        }

        // 404 errors
        if ($statusCode === 404) {
            return 'The page you are looking for could not be found.';
        }

        // 500 errors
        if ($statusCode === 500) {
            return 'We encountered an unexpected error. Our team has been notified and is working on a fix. Please try again later.';
        }

        // 503 errors (maintenance)
        if ($statusCode === 503) {
            return 'We are currently performing maintenance. Please check back soon.';
        }

        // Generic message
        return 'An error occurred. Please try again or contact support if the problem persists.';
    }
}
