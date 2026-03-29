<?php

namespace Mostafaarafat\DataChat\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Mostafaarafat\DataChat\Models\ChatConfig;
use Mostafaarafat\DataChat\Models\Suggestion;
use Mostafaarafat\DataChat\Models\Usage;

class DashboardController extends Controller
{
    /**
     * Show all widgets
     */
    public function index()
    {
        $configs = ChatConfig::where('user_id', Auth::id())
            ->withCount('conversations')
            ->latest()
            ->get();

        return view('datachat::dashboard.index', compact('configs'));
    }

    /**
     * Show widget details
     */
    public function show(int $id)
    {
        $config = ChatConfig::where('user_id', Auth::id())
            ->findOrFail($id);

        $conversations = $config->conversations()
            ->with(['messages' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->latest('last_message_at')
            ->paginate(20);

        $usage = Usage::where('config_id', $id)
            ->where('date', '>=', now()->subDays(30))
            ->orderBy('date')
            ->get();

        $suggestions = $config->suggestions()->ordered()->get();

        return view('datachat::dashboard.show', compact('config', 'conversations', 'usage', 'suggestions'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('datachat::dashboard.create');
    }

    /**
     * Store new widget
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'widget_name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'position' => 'required|in:bottom-right,bottom-left',
            'allowed_domains' => 'nullable|array',
            'allowed_domains.*' => 'string',
            'max_messages_per_day' => 'required|integer|min:10|max:10000',
            'greeting_message' => 'nullable|string|max:500',
        ]);

        $config = ChatConfig::create(array_merge($validated, [
            'user_id' => Auth::id(),
        ]));

        // Create default suggestions
        $defaultSuggestions = [
            'How many users do we have?',
            'What is our revenue this month?',
            'Show me recent orders',
        ];

        foreach ($defaultSuggestions as $index => $question) {
            Suggestion::create([
                'config_id' => $config->id,
                'question' => $question,
                'display_order' => $index,
            ]);
        }

        return redirect()
            ->route('datachat.show', $config->id)
            ->with('success', 'Widget created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(int $id)
    {
        $config = ChatConfig::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('datachat::dashboard.edit', compact('config'));
    }

    /**
     * Update widget
     */
    public function update(Request $request, int $id)
    {
        $config = ChatConfig::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'widget_name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'position' => 'required|in:bottom-right,bottom-left',
            'allowed_domains' => 'nullable|array',
            'allowed_domains.*' => 'string',
            'max_messages_per_day' => 'required|integer|min:10|max:10000',
            'greeting_message' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $config->update($validated);

        return redirect()
            ->route('datachat.show', $config->id)
            ->with('success', 'Widget updated successfully!');
    }

    /**
     * Delete widget
     */
    public function destroy(int $id)
    {
        $config = ChatConfig::where('user_id', Auth::id())
            ->findOrFail($id);

        $config->delete();

        return redirect()
            ->route('datachat.index')
            ->with('success', 'Widget deleted successfully!');
    }

    /**
     * Regenerate API key
     */
    public function regenerateKey(int $id)
    {
        $config = ChatConfig::where('user_id', Auth::id())
            ->findOrFail($id);

        $newKey = $config->regenerateApiKey();

        return back()->with('success', 'API key regenerated successfully!');
    }
}