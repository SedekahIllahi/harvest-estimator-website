<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Farmer - TaniCheck</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Open Sans"', 'sans-serif'],
                        heading: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-[#09090b] font-sans antialiased text-zinc-200 min-h-screen">

    <div class="p-6 sm:p-10 max-w-3xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white tracking-tight">Edit Farmer Profile</h1>
                <p class="text-sm font-medium text-zinc-400 mt-1">Update the personal details for <span class="text-emerald-400 font-semibold">{{ $farmer->name }}</span>.</p>
            </div>
            <a href="{{ route('admin.farmers.index') }}" class="text-zinc-400 hover:text-white font-semibold transition-colors flex items-center gap-2 bg-zinc-800/50 hover:bg-zinc-800 px-4 py-2 rounded-lg border border-zinc-700/50">
                &larr; Back to List
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-zinc-900/80 rounded-2xl shadow-xl shadow-black/20 border border-zinc-800/60 overflow-hidden">
            <form action="{{ route('admin.farmers.update', $farmer->id) }}" method="POST" class="p-6 lg:p-8">
                @csrf
                @method('PUT') 
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-bold text-zinc-400 mb-2 uppercase tracking-wider">Full Name</label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', $farmer->name) }}" 
                           class="w-full px-4 py-3 rounded-lg bg-zinc-900 border border-zinc-700 text-white placeholder-zinc-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-sm"
                           required>
                    @error('name')
                        <p class="text-red-400 text-xs font-medium mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="phone" class="block text-sm font-bold text-zinc-400 mb-2 uppercase tracking-wider">Phone Number</label>
                    <input type="text" id="phone" name="phone" 
                           value="{{ old('phone', $farmer->phone) }}" 
                           class="w-full px-4 py-3 rounded-lg bg-zinc-900 border border-zinc-700 text-white placeholder-zinc-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-sm"
                           required>
                    @error('phone')
                        <p class="text-red-400 text-xs font-medium mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t border-zinc-800/60 pt-6 mt-6">
                    <a href="{{ route('admin.farmers.index') }}" class="w-full sm:w-auto px-6 py-2.5 text-zinc-300 font-bold hover:bg-zinc-800 border border-transparent hover:border-zinc-700 rounded-lg transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-8 rounded-lg shadow-lg shadow-emerald-900/30 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-save text-sm"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>