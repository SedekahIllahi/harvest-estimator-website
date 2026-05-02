<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Farmers - TaniCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div class="p-8 max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Farmer Management</h1>
                <p class="text-sm text-gray-500">View, edit, and manage all registered farmers in the system.</p>
            </div>
            <a href="{{ route('admin.register-farmer') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                + Register New Farmer
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-b">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Phone Number</th>
                        <th class="px-6 py-4">Registered Lands</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($farmers as $farmer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $farmer->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $farmer->phone }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                                {{ $farmer->lands->count() ?? 0 }} Fields
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            
                            <a href="{{ route('admin.farmers.edit', $farmer->id) }}" class="text-blue-500 hover:text-blue-700 font-medium">Edit</a>     
                                                   
                            <form action="{{ route('admin.farmers.reset-pin', $farmer->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset PIN to 123456?');">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 font-medium">Reset PIN</button>
                            </form>

                            <form action="{{ route('admin.farmers.destroy', $farmer->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this farmer? This might delete their land data too!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $farmers->links() }}
            </div>
        </div>
        
    </div>

</body>
</html>