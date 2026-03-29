import React, { useState, useEffect, useRef } from 'react';
import { fetchConfig, sendMessage, pollMessages } from '../api/datachat';
import MessageList from './MessageList';
import MessageInput from './MessageInput';
import SuggestedQuestions from './SuggestedQuestions';

export default function ChatWidget({ config }) {
    const [isOpen, setIsOpen] = useState(false);
    const [widgetConfig, setWidgetConfig] = useState(null);
    const [messages, setMessages] = useState([]);
    const [conversationId, setConversationId] = useState(null);
    const [sessionId] = useState(() => generateSessionId());
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);
    const pollIntervalRef = useRef(null);
    const lastMessageIdRef = useRef(0);

    // Load widget configuration
    useEffect(() => {
        fetchConfig(config.apiKey, config.apiUrl)
            .then(data => {
                setWidgetConfig(data);
                console.log('DataChat config loaded:', data);
            })
            .catch(err => {
                console.error('Failed to load DataChat config:', err);
                setError('Failed to initialize widget. Please check your API key.');
            });
    }, [config.apiKey, config.apiUrl]);

    // Poll for new messages when conversation is active
    useEffect(() => {
        if (!isOpen || !conversationId || !isLoading) {
            if (pollIntervalRef.current) {
                clearInterval(pollIntervalRef.current);
                pollIntervalRef.current = null;
            }
            return;
        }

        // Start polling
        pollIntervalRef.current = setInterval(async () => {
            try {
                const data = await pollMessages(
                    config.apiKey,
                    config.apiUrl,
                    conversationId,
                    lastMessageIdRef.current
                );

                if (data.messages && data.messages.length > 0) {
                    setMessages(prev => {
                        const newMessages = [...prev, ...data.messages];
                        // Update last message ID
                        if (newMessages.length > 0) {
                            lastMessageIdRef.current = newMessages[newMessages.length - 1].id;
                        }
                        return newMessages;
                    });
                    setIsLoading(false);
                }
            } catch (err) {
                console.error('Poll error:', err);
            }
        }, 2000); // Poll every 2 seconds

        return () => {
            if (pollIntervalRef.current) {
                clearInterval(pollIntervalRef.current);
            }
        };
    }, [isOpen, conversationId, isLoading, config]);

    // Handle sending a message
    const handleSendMessage = async (text) => {
        if (!text.trim()) return;

        // Create optimistic user message
        const userMessage = {
            id: Date.now(),
            role: 'user',
            content: text.trim(),
            created_at: new Date().toISOString(),
            has_error: false,
        };

        setMessages(prev => {
            const newMessages = [...prev, userMessage];
            lastMessageIdRef.current = userMessage.id;
            return newMessages;
        });
        setIsLoading(true);
        setError(null);

        try {
            const response = await sendMessage(
                config.apiKey,
                config.apiUrl,
                text.trim(),
                sessionId,
                config.userId,
                config.metadata
            );

            if (response.conversation_id) {
                setConversationId(response.conversation_id);
            }
        } catch (err) {
            console.error('Send error:', err);
            setError(err.message || 'Failed to send message');
            setIsLoading(false);

            // Add error message
            setMessages(prev => [...prev, {
                id: Date.now(),
                role: 'assistant',
                content: 'Sorry, I encountered an error. Please try again.',
                created_at: new Date().toISOString(),
                has_error: true,
            }]);
        }
    };

    const handleSuggestionClick = (question) => {
        handleSendMessage(question);
    };

    const handleToggle = () => {
        setIsOpen(!isOpen);
        if (!isOpen && messages.length === 0 && widgetConfig?.greeting_message) {
            // Add greeting message on first open
            setMessages([{
                id: 0,
                role: 'assistant',
                content: widgetConfig.greeting_message,
                created_at: new Date().toISOString(),
                has_error: false,
            }]);
        }
    };

    if (!widgetConfig && !error) {
        return null; // Loading
    }

    if (error && !widgetConfig) {
        return (
            <div style={{
                position: 'fixed',
                bottom: '20px',
                right: '20px',
                background: '#ef4444',
                color: 'white',
                padding: '12px 16px',
                borderRadius: '8px',
                fontSize: '14px',
                maxWidth: '300px',
                zIndex: 9999,
            }}>
                {error}
            </div>
        );
    }

    const position = widgetConfig.position || 'bottom-right';
    const primaryColor = widgetConfig.primary_color || '#3b82f6';

    return (
        <div className="datachat-widget">
            {/* Chat Button */}
            {!isOpen && (
                <button
                    onClick={handleToggle}
                    className="datachat-fade-in"
                    style={{
                        position: 'fixed',
                        [position.includes('right') ? 'right' : 'left']: '20px',
                        bottom: '20px',
                        width: '60px',
                        height: '60px',
                        borderRadius: '50%',
                        backgroundColor: primaryColor,
                        border: 'none',
                        cursor: 'pointer',
                        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        zIndex: 9999,
                        transition: 'transform 0.2s, box-shadow 0.2s',
                    }}
                    onMouseEnter={(e) => {
                        e.target.style.transform = 'scale(1.1)';
                        e.target.style.boxShadow = '0 6px 16px rgba(0,0,0,0.2)';
                    }}
                    onMouseLeave={(e) => {
                        e.target.style.transform = 'scale(1)';
                        e.target.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                    }}
                >
                    <ChatIcon color="#ffffff" />
                </button>
            )}

            {/* Chat Window */}
            {isOpen && (
                <div
                    className="datachat-window datachat-slide-up"
                    style={{
                        position: 'fixed',
                        [position.includes('right') ? 'right' : 'left']: '20px',
                        bottom: '20px',
                        width: '380px',
                        height: '600px',
                        maxHeight: 'calc(100vh - 40px)',
                        backgroundColor: '#ffffff',
                        borderRadius: '12px',
                        boxShadow: '0 8px 24px rgba(0,0,0,0.15)',
                        display: 'flex',
                        flexDirection: 'column',
                        zIndex: 9999,
                        overflow: 'hidden',
                    }}
                >
                    {/* Header */}
                    <div
                        style={{
                            padding: '16px 20px',
                            backgroundColor: primaryColor,
                            color: '#ffffff',
                            borderTopLeftRadius: '12px',
                            borderTopRightRadius: '12px',
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            flexShrink: 0,
                        }}
                    >
                        <div>
                            <h3 style={{ margin: 0, fontSize: '18px', fontWeight: 600 }}>
                                {widgetConfig.name}
                            </h3>
                            <p style={{ margin: '4px 0 0', fontSize: '12px', opacity: 0.9 }}>
                                Ask me anything about your data
                            </p>
                        </div>
                        <button
                            onClick={handleToggle}
                            style={{
                                background: 'none',
                                border: 'none',
                                color: '#ffffff',
                                cursor: 'pointer',
                                fontSize: '28px',
                                padding: 0,
                                lineHeight: 1,
                                width: '32px',
                                height: '32px',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                borderRadius: '4px',
                                transition: 'background-color 0.2s',
                            }}
                            onMouseEnter={(e) => {
                                e.target.style.backgroundColor = 'rgba(255,255,255,0.1)';
                            }}
                            onMouseLeave={(e) => {
                                e.target.style.backgroundColor = 'transparent';
                            }}
                        >
                            ×
                        </button>
                    </div>

                    {/* Error Banner */}
                    {error && (
                        <div style={{
                            padding: '12px 16px',
                            backgroundColor: '#fee',
                            color: '#c00',
                            fontSize: '13px',
                            borderBottom: '1px solid #fcc',
                        }}>
                            {error}
                        </div>
                    )}

                    {/* Messages Area */}
                    <div
                        className="datachat-messages"
                        style={{
                            flex: 1,
                            overflowY: 'auto',
                            padding: '16px',
                            backgroundColor: '#f9fafb',
                        }}
                    >
                        {messages.length === 0 && widgetConfig.suggestions && widgetConfig.suggestions.length > 0 ? (
                            <SuggestedQuestions
                                suggestions={widgetConfig.suggestions}
                                onSelect={handleSuggestionClick}
                                primaryColor={primaryColor}
                            />
                        ) : (
                            <MessageList
                                messages={messages}
                                isLoading={isLoading}
                                primaryColor={primaryColor}
                            />
                        )}
                    </div>

                    {/* Input Area */}
                    <MessageInput
                        onSend={handleSendMessage}
                        disabled={isLoading}
                        primaryColor={primaryColor}
                    />

                    {/* Powered by badge */}
                    <div style={{
                        padding: '8px',
                        textAlign: 'center',
                        fontSize: '11px',
                        color: '#9ca3af',
                        borderTop: '1px solid #e5e7eb',
                    }}>
                        Powered by DataChat
                    </div>
                </div>
            )}
        </div>
    );
}

// Helper Components
function ChatIcon({ color }) {
    return (
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        </svg>
    );
}

function generateSessionId() {
    return 'session_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
}