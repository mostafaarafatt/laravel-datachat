<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Widget - DataChat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('datachat.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Widget</h1>
                    <p class="text-sm text-gray-600">Configure your AI chat widget</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('datachat.store') }}" method="POST" class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            @csrf

            <!-- Widget Name -->
            <div>
                <label for="widget_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Widget Name
                </label>
                <input
                        type="text"
                        name="widget_name"
                        id="widget_name"
                        value="{{ old('widget_name') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="e.g., Customer Support Chat"
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
                            value="{{ old('primary_color', '#3b82f6') }}"
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
                    <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input
                                type="radio"
                                name="position"
                                value="bottom-right"
                                {{ old('position', 'bottom-right') == 'bottom-right' ? 'checked' : '' }}
                                class="sr-only"
                        >
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Bottom Right</p>
                            <p class="text-sm text-gray-500">Default position</p>
                        </div>
                        <svg class="w-5 h-5 text-blue-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </label>
                    <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input
                                type="radio"
                                name="position"
                                value="bottom-left"
                                {{ old('position') == 'bottom-left' ? 'checked' : '' }}
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
                        placeholder="example.com&#10;app.example.com&#10;(Leave empty to allow all domains)"
                >{{ old('allowed_domains') ? implode("\n", old('allowed_domains')) : '' }}</textarea>
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
                        value="{{ old('max_messages_per_day', 100) }}"
                        min="10"
                        max="10000"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <p class="mt-1 text-sm text-gray-500">Maximum messages per day to prevent abuse</p>
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
                        placeholder="Hi! I'm here to help you find insights in your data. What would you like to know?"
                >{{ old('greeting_message') }}</textarea>
                @error('greeting_message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t">
                <a href="{{ route('datachat.index') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Cancel
                </a>
                <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition"
                >
                    Create Widget
                </button>
            </div>
        </form>
    </main>
</div>
</body>
</html>