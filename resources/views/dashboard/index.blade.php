<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataChat - My Widgets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">DataChat</h1>
                    <p class="text-sm text-gray-600">AI-powered chat widgets for your database</p>
                </div>
                <a href="{{ route('datachat.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    + Create Widget
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($configs->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No widgets yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first chat widget.</p>
                <div class="mt-6">
                    <a href="{{ route('datachat.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Create Widget
                    </a>
                </div>
            </div>
        @else
            <!-- Widgets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($configs as $config)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $config->is_active ? 'Active' : 'Inactive' }}
                            </span>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded" style="background-color: {{ $config->primary_color }}"></div>
                                    <span class="text-xs text-gray-500">{{ ucfirst(str_replace('-', ' ', $config->position)) }}</span>
                                </div>
                            </div>

                            <!-- Widget Name -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $config->widget_name }}</h3>

                            <!-- Stats -->
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>{{ $config->conversations_count }} conversations</span>
                                </div>
                            </div>

                            <!-- API Key -->
                            <div class="mb-4">
                                <label class="block text-xs font-medium text-gray-700 mb-1">API Key</label>
                                <code class="block text-xs bg-gray-100 p-2 rounded border border-gray-200 truncate">
                                    {{ $config->api_key }}
                                </code>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('datachat.show', $config->id) }}"
                                   class="flex-1 text-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium transition">
                                    View Details
                                </a>
                                <a href="{{ route('datachat.edit', $config->id) }}"
                                   class="px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
</body>
</html>