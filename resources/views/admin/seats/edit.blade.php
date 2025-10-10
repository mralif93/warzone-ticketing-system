@extends('layouts.admin')

@section('title', 'Edit Seat')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-wwc-neutral-900 font-display">Edit Seat</h1>
            <p class="text-wwc-neutral-600 mt-1">Seat: {{ $seat->seat_identifier }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.seats.show', $seat) }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                <i class='bx bx-show text-lg mr-2'></i>
                View Seat
            </a>
            <a href="{{ route('admin.seats.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                <i class='bx bx-arrow-back text-lg mr-2'></i>
                Back to Seats
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200 p-6">
        <form method="POST" action="{{ route('admin.seats.update', $seat) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Seat Identifier -->
                <div class="md:col-span-2">
                    <label for="seat_identifier" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                        Seat Identifier <span class="text-wwc-error">*</span>
                    </label>
                    <input type="text" name="seat_identifier" id="seat_identifier" value="{{ old('seat_identifier', $seat->seat_identifier) }}" required
                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('seat_identifier') border-wwc-error @enderror"
                           placeholder="e.g., A-1-1, VIP-2-5">
                    @error('seat_identifier')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Zone -->
                <div>
                    <label for="price_zone" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                        Price Zone <span class="text-wwc-error">*</span>
                    </label>
                    <select name="price_zone" id="price_zone" required
                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('price_zone') border-wwc-error @enderror">
                        <option value="">Select Price Zone</option>
                        @foreach($priceZones as $zone)
                            <option value="{{ $zone->name }}" {{ old('price_zone', $seat->price_zone) == $zone->name ? 'selected' : '' }}>
                                {{ $zone->name }} - RM{{ number_format($zone->base_price, 0) }}
                            </option>
                        @endforeach
                    </select>
                    @error('price_zone')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seat Type -->
                <div>
                    <label for="seat_type" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                        Seat Type <span class="text-wwc-error">*</span>
                    </label>
                    <select name="seat_type" id="seat_type" required
                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('seat_type') border-wwc-error @enderror">
                        <option value="">Select Seat Type</option>
                        <option value="Standard" {{ old('seat_type', $seat->seat_type) == 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="VIP" {{ old('seat_type', $seat->seat_type) == 'VIP' ? 'selected' : '' }}>VIP</option>
                        <option value="Premium" {{ old('seat_type', $seat->seat_type) == 'Premium' ? 'selected' : '' }}>Premium</option>
                        <option value="Box" {{ old('seat_type', $seat->seat_type) == 'Box' ? 'selected' : '' }}>Box</option>
                        <option value="Standing" {{ old('seat_type', $seat->seat_type) == 'Standing' ? 'selected' : '' }}>Standing</option>
                    </select>
                    @error('seat_type')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Base Price -->
                <div>
                    <label for="base_price" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                        Base Price (RM) <span class="text-wwc-error">*</span>
                    </label>
                    <input type="number" name="base_price" id="base_price" step="0.01" min="0" value="{{ old('base_price', $seat->base_price) }}" required
                           class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('base_price') border-wwc-error @enderror"
                           placeholder="0.00">
                    @error('base_price')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-wwc-neutral-900 mb-2">
                        Status <span class="text-wwc-error">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="block w-full px-3 py-2 border border-wwc-neutral-300 rounded-2xl focus:ring-2 focus:ring-wwc-primary focus:border-wwc-primary text-sm @error('status') border-wwc-error @enderror">
                        <option value="Available" {{ old('status', $seat->status) == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Occupied" {{ old('status', $seat->status) == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="Maintenance" {{ old('status', $seat->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-wwc-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-wwc-neutral-200">
                <a href="{{ route('admin.seats.show', $seat) }}" 
                   class="px-6 py-2 bg-wwc-neutral-100 text-wwc-neutral-700 rounded-xl text-sm font-semibold hover:bg-wwc-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-neutral-500 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-wwc-primary text-white rounded-xl text-sm font-semibold hover:bg-wwc-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wwc-primary transition-colors duration-200">
                    <i class='bx bx-save text-lg mr-2'></i>
                    Update Seat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill base price when price zone is selected
document.getElementById('price_zone').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    if (selectedOption.value) {
        const priceText = selectedOption.textContent;
        const priceMatch = priceText.match(/RM([\d,]+)/);
        if (priceMatch) {
            const price = priceMatch[1].replace(/,/g, '');
            document.getElementById('base_price').value = price;
        }
    }
});
</script>
@endsection
