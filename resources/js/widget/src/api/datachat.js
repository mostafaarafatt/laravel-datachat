/**
 * Fetch widget configuration
 */
export async function fetchConfig(apiKey, apiUrl) {
    const response = await fetch(`${apiUrl}/api/datachat/config`, {
        method: 'GET',
        headers: {
            'X-DataChat-Key': apiKey,
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || error.error || 'Failed to fetch config');
    }

    return response.json();
}

/**
 * Send a message
 */
export async function sendMessage(apiKey, apiUrl, message, sessionId, userId, metadata) {
    const response = await fetch(`${apiUrl}/api/datachat/message`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-DataChat-Key': apiKey,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            message,
            session_id: sessionId,
            user_id: userId || null,
            metadata: metadata || {},
        }),
    });

    if (!response.ok) {
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || error.error || 'Failed to send message');
    }

    return response.json();
}

/**
 * Poll for new messages
 */
export async function pollMessages(apiKey, apiUrl, conversationId, afterId) {
    const response = await fetch(
        `${apiUrl}/api/datachat/conversation/${conversationId}/poll?after_id=${afterId}`,
        {
            method: 'GET',
            headers: {
                'X-DataChat-Key': apiKey,
                'Accept': 'application/json',
            },
        }
    );

    if (!response.ok) {
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || error.error || 'Failed to poll messages');
    }

    return response.json();
}

/**
 * Get conversation history
 */
export async function getConversation(apiKey, apiUrl, conversationId) {
    const response = await fetch(
        `${apiUrl}/api/datachat/conversation/${conversationId}`,
        {
            method: 'GET',
            headers: {
                'X-DataChat-Key': apiKey,
                'Accept': 'application/json',
            },
        }
    );

    if (!response.ok) {
        const error = await response.json().catch(() => ({}));
        throw new Error(error.message || error.error || 'Failed to fetch conversation');
    }

    return response.json();
}