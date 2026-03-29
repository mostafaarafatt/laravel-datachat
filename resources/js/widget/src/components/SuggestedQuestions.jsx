import React from 'react';

export default function SuggestedQuestions({ suggestions, onSelect, primaryColor }) {
    if (!suggestions || suggestions.length === 0) {
        return (
            <div style={{
                textAlign: 'center',
                color: '#6b7280',
                padding: '40px 20px',
            }}>
                <div style={{ fontSize: '48px', marginBottom: '16px' }}>💬</div>
                <p style={{ margin: 0, fontSize: '15px', fontWeight: 500 }}>
                    Hi! I'm here to help.
                </p>
                <p style={{ margin: '8px 0 0', fontSize: '13px' }}>
                    Ask me anything about your data.
                </p>
            </div>
        );
    }

    return (
        <div style={{ padding: '8px 0' }} className="datachat-fade-in">
            <p style={{
                fontSize: '13px',
                color: '#6b7280',
                marginBottom: '12px',
                fontWeight: 500,
            }}>
                💡 Try asking:
            </p>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
                {suggestions.map((question, index) => (
                    <button
                        key={index}
                        onClick={() => onSelect(question)}
                        style={{
                            padding: '14px 16px',
                            backgroundColor: '#ffffff',
                            border: `1px solid ${primaryColor}33`,
                            borderRadius: '10px',
                            fontSize: '14px',
                            color: '#374151',
                            cursor: 'pointer',
                            textAlign: 'left',
                            transition: 'all 0.2s',
                            fontWeight: 400,
                            lineHeight: '1.5',
                        }}
                        onMouseEnter={(e) => {
                            e.target.style.backgroundColor = `${primaryColor}15`;
                            e.target.style.borderColor = primaryColor;
                            e.target.style.transform = 'translateY(-2px)';
                            e.target.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                        }}
                        onMouseLeave={(e) => {
                            e.target.style.backgroundColor = '#ffffff';
                            e.target.style.borderColor = `${primaryColor}33`;
                            e.target.style.transform = 'translateY(0)';
                            e.target.style.boxShadow = 'none';
                        }}
                    >
                        <span style={{ marginRight: '8px', opacity: 0.6 }}>→</span>
                        {question}
                    </button>
                ))}
            </div>
        </div>
    );
}