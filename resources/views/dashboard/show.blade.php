<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->widget_name }} - DataChat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('datachat.index') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $config->widget_name }}</h1>
                        <p class="text-sm text-gray-600">Widget Configuration & Analytics</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('datachat.edit', $config->id) }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                        Edit
                    </a>
                    <form action="{{ route('datachat.destroy', $config->id) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this widget?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            Delete
                        </button>
                    </form>
                </div>
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Conversations</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $config->conversations()->count() }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Messages Today</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $config->getTodayUsage()?->message_count ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>Limit: {{ $config->max_messages_per_day }}</span>
                        <span>{{ round(($config->getTodayUsage()?->message_count ?? 0) / $config->max_messages_per_day * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                        <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ min(100, round(($config->getTodayUsage()?->message_count ?? 0) / $config->max_messages_per_day * 100)) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">API Cost (30d)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            ${{ number_format($usage->sum('ai_api_cost'), 2) }}
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-2xl font-bold mt-1 {{ $config->is_active ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $config->is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                    <div class="{{ $config->is_active ? 'bg-green-100' : 'bg-gray-100' }} rounded-full p-3">
                        <svg class="w-6 h-6 {{ $config->is_active ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Configuration -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Installation Code -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Installation Code</h2>
                    <p class="text-sm text-gray-600 mb-4">Add this code to your website to embed the widget:</p>

                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm text-gray-100"><code>&lt;!-- DataChat Widget --&gt;
&lt;script src="{{ config('app.url') }}/vendor/datachat/datachat-widget.umd.js"&gt;&lt;/script&gt;
&lt;link rel="stylesheet" href="{{ config('app.url') }}/vendor/datachat/datachat-widget.css"&gt;

&lt;script&gt;
  DataChat.init({
    apiKey: '{{ $config->api_key }}',
    apiUrl: '{{ config('app.url') }}',
    userId: 'USER_ID_HERE', // Optional
    metadata: {} // Optional
  });
&lt;/script&gt;</code></pre>
                    </div>

                    <button onclick="copyCode()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                        Copy Code
                    </button>
                </div>

                <!-- Usage Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Message Volume (Last 30 Days)</h2>
                    <canvas id="usageChart"></canvas>
                </div>

                <!-- Recent Conversations -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Conversations</h2>

                    @if($conversations->isEmpty())
                        <p class="text-gray-500 text-center py-8">No conversations yet</p>
                    @else
                        <div class="space-y-4">
                            @foreach($conversations as $conversation)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            Session: {{ Str::limit($conversation->session_id, 20) }}
                                        </span>
                                            @if($conversation->end_user_id)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">
                                            User: {{ $conversation->end_user_id }}
                                        </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500">
                                        {{ $conversation->last_message_at->diffForHumans() }}
                                    </span>
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        {{ $conversation->message_count }} messages
                                    </div>

                                    @if($conversation->messages->isNotEmpty())
                                        <div class="mt-2 text-sm text-gray-500 italic">
                                            Last: "{{ Str::limit($conversation->messages->last()->content, 80) }}"
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $conversations->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Widget Preview -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Widget Preview</h2>
                    <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                        <div class="absolute {{ $config->position === 'bottom-right' ? 'bottom-4 right-4' : 'bottom-4 left-4' }}">
                            <div class="w-12 h-12 rounded-full" style="background-color: {{ $config->primary_color }}"></div>
                        </div>
                        <p class="text-sm text-gray-500">Position: {{ ucfirst(str_replace('-', ' ', $config->position)) }}</p>
                    </div>
                </div>

                <!-- API Key -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">API Key</h2>
                    <code class="block text-xs bg-gray-100 p-3 rounded border border-gray-200 break-all">
                        {{ $config->api_key }}
                    </code>
                    <form action="{{ route('datachat.regenerate-key', $config->id) }}" method="POST" class="mt-3"
                          onsubmit="return confirm('This will invalidate the current API key. Continue?');">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm font-medium transition">
                            Regenerate Key
                        </button>
                    </form>
                </div>

                <!-- Suggested Questions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Suggested Questions</h2>
                    @if($suggestions->isEmpty())
                        <p class="text-sm text-gray-500">No suggestions configured</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($suggestions as $suggestion)
                                <li class="text-sm text-gray-700 flex items-start gap-2">
                                    <span class="text-blue-600 mt-0.5">→</span>
                                    <span>{{ $suggestion->question }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Settings Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Settings</h2>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-600">Daily Limit</dt>
                            <dd class="font-medium text-gray-900">{{ $config->max_messages_per_day }} messages</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Rate Limit</dt>
                            <dd class="font-medium text-gray-900">{{ $config->max_messages_per_minute }}/minute</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Allowed Domains</dt>
                            <dd class="font-medium text-gray-900">
                                @if(empty($config->allowed_domains))
                                    All domains
                                @else
                                    {{ count($config->allowed_domains) }} domain(s)
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Copy installation code
    function copyCode() {
        const code = document.querySelector('pre code').textContent;
        navigator.clipboard.writeText(code).then(() => {
            alert('Code copied to clipboard!');
        });
    }

    // Usage chart
    const ctx = document.getElementById('usageChart').getContext('2d');
    const usageData = @json($usage);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: usageData.map(u => u.date),
            datasets: [{
                label: 'Messages',
                data: usageData.map(u => u.message_count),
                borderColor: '{{ $config->primary_color }}',
                backgroundColor: '{{ $config->primary_color }}33',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
</body>
</html>