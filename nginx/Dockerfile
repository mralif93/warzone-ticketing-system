FROM nginx:alpine

# Install additional packages
RUN apk add --no-cache \
    curl \
    netcat-openbsd

# Copy Nginx configuration files
COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/ssl.conf /etc/nginx/ssl.conf

# Create log directories
RUN mkdir -p /var/log/nginx

# Set proper permissions
RUN chown -R nginx:nginx /var/log/nginx

# Expose ports
EXPOSE 80 443

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Start Nginx
CMD ["nginx", "-g", "daemon off;"]
