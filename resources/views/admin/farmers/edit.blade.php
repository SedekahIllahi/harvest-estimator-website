<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Farmer - TaniCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div class="p-8 max-w-3xl mx-auto">
        
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Farmer Profile</h1>
                <p class="text-sm text-gray-500">Update the personal details for {{ $farmer->name }}.</p>
            </div>
            <a href="{{ route('admin.farmers.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition">
                &larr; Back to List
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.farmers.update', $farmer->id) }}" method="POST" class="p-6 lg:p-8">
                @csrf
                @method('PUT') <div class="mb-6">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', $farmer->name) }}" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <input type="text" id="phone" name="phone" 
                           value="{{ old('phone', $farmer->phone) }}" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                           required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-4 border-t pt-6">
                    <a href="{{ route('admin.farmers.index') }}" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-50 rounded-lg transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>