import React, { useEffect, useRef } from 'react';

export default function MessageList({ messages, isLoading, primaryColor }) {
    const bottomRef = useRef(null);

    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages, isLoading]);

    return (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
            {messages.map((message, index) => (
                <div
                    key={message.id || index}
                    className="datachat-fade-in"
                    style={{
                        display: 'flex',
                        justifyContent: message.role === 'user' ? 'flex-end' : 'flex-start',
                    }}
                >
                    <div
                        style={{
                            maxWidth: '80%',
                            padding: '12px 16px',
                            borderRadius: message.role === 'user' ? '16px 16px 4px 16px' : '16px 16px 16px 4px',
                            backgroundColor: message.role === 'user' ? primaryColor : '#ffffff',
                            color: message.role === 'user' ? '#ffffff' : '#1f2937',
                            fontSize: '14px',
                            lineHeight: '1.5',
                            wordWrap: 'break-word',
                            boxShadow: '0 1px 2px rgba(0,0,0,0.1)',
                            border: message.role === 'assistant' ? '1px solid #e5e7eb' : 'none',
                        }}
                    >
                        {message.has_error && (
                            <div style={{
                                fontSize: '12px',
                                color: '#dc2626',
                                marginBottom: '4px',
                                fontWeight: 500,
                            }}>
                                ⚠️ Error
                            </div>
                        )}
                        {message.content}
                        <div style={{
                            fontSize: '11px',
                            marginTop: '6px',
                            opacity: 0.7,
                        }}>
                            {formatTime(message.created_at)}
                        </div>
                    </div>
                </div>
            ))}

            {isLoading && (
                <div style={{ display: 'flex', justifyContent: 'flex-start' }}>
                    <div
                        style={{
                            padding: '12px 16px',
                            borderRadius: '16px 16px 16px 4px',
                            backgroundColor: '#ffffff',
                            boxShadow: '0 1px 2px rgba(0,0,0,0.1)',
                            border: '1px solid #e5e7eb',
                        }}
                    >
                        <TypingIndicator />
                    </div>
                </div>
            )}

            <div ref={bottomRef} />
        </div>
    );
}

function TypingIndicator() {
    return (
        <div style={{ display: 'flex', gap: '4px', alignItems: 'center', padding: '4px 0' }}>
            <span className="typing-dot" />
            <span className="typing-dot" />
            <span className="typing-dot" />
        </div>
    );
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);

    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins}m ago`;

    const diffHours = Math.floor(diffMins / 60);
    if (diffHours < 24) return `${diffHours}h ago`;

    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}