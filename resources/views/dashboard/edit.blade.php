<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $config->widget_name }} - DataChat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('datachat.show', $config->id) }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Widget</h1>
                    <p class="text-sm text-gray-600">{{ $config->widget_name }}</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('datachat.update', $config->id) }}" method="POST" class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Widget Name -->
            <div>
                <label for="widget_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Widget Name
                </label>
                <input
                        type="text"
                        name="widget_name"
                        id="widget_name"
                        value="{{ old('widget_name', $config->widget_name) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                @error('widget_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Primary Color -->
            <div>
                <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                    Primary Color
                </label>
                <div class="flex items-center gap-3">
                    <input
                            type="color"
                            name="primary_color"
                            id="primary_color"
                            value="{{ old('primary_color', $config->primary_color) }}"
                            required
                            class="h-10 w-20 border border-gray-300 rounded cursor-pointer"
                    >
                    <span class="text-sm text-gray-600">Choose your brand color</span>
                </div>
                @error('primary_color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Widget Position
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center p-4 border-2 {{ old('position', $config->position) == 'bottom-right' ? 'border-blue-500' : 'border-gray-300' }} rounded-lg cursor-pointer hover:border-blue-500">
                        <input
                                type="radio"
                                name="position"
                                value="bottom-right"
                                {{ old('position', $config->position) == 'bottom-right' ? 'checked' : '' }}
                                class="sr-only"
                        >
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Bottom Right</p>
                            <p class="text-sm text-gray-500">Default position</p>
                        </div>
                    </label>
                    <label class="relative flex items-center p-4 border-2 {{ old('position', $config->position) == 'bottom-left' ? 'border-blue-500' : 'border-gray-300' }} rounded-lg cursor-pointer hover:border-blue-500">
                        <input
                                type="radio"
                                name="position"
                                value="bottom-left"
                                {{ old('position', $config->position) == 'bottom-left' ? 'checked' : '' }}
                                class="sr-only"
                        >
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Bottom Left</p>
                            <p class="text-sm text-gray-500">Alternative position</p>
                        </div>
                    </label>
                </div>
                @error('position')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Allowed Domains -->
            <div>
                <label for="allowed_domains" class="block text-sm font-medium text-gray-700 mb-2">
                    Allowed Domains (Optional)
                </label>
                <textarea
                        name="allowed_domains[]"
                        id="allowed_domains"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="example.com&#10;app.example.com"
                >{{ old('allowed_domains') ? implode("\n", old('allowed_domains')) : (is_array($config->allowed_domains) ? implode("\n", $config->allowed_domains) : '') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">One domain per line. Leave empty to allow all.</p>
                @error('allowed_domains')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message Limit -->
            <div>
                <label for="max_messages_per_day" class="block text-sm font-medium text-gray-700 mb-2">
                    Daily Message Limit
                </label>
                <input
                        type="number"
                        name="max_messages_per_day"
                        id="max_messages_per_day"
                        value="{{ old('max_messages_per_day', $config->max_messages_per_day) }}"
                        min="10"
                        max="10000"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                @error('max_messages_per_day')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Greeting Message -->
            <div>
                <label for="greeting_message" class="block text-sm font-medium text-gray-700 mb-2">
                    Greeting Message (Optional)
                </label>
                <textarea
                        name="greeting_message"
                        id="greeting_message"
                        rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >{{ old('greeting_message', $config->greeting_message) }}</textarea>
                @error('greeting_message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div>
                <label class="flex items-center gap-2">
                    <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $config->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                    >
                    <span class="text-sm font-medium text-gray-700">Widget is active</span>
                </label>
                @error('is_active')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t">
                <a href="{{ route('datachat.show', $config->id) }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Cancel
                </a>
                <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </main>
</div>
</body>
</html>