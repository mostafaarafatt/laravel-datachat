import React, { useState, useRef, useEffect } from 'react';

export default function MessageInput({ onSend, disabled, primaryColor }) {
    const [input, setInput] = useState('');
    const textareaRef = useRef(null);

    useEffect(() => {
        if (textareaRef.current) {
            textareaRef.current.style.height = 'auto';
            textareaRef.current.style.height = Math.min(textareaRef.current.scrollHeight, 120) + 'px';
        }
    }, [input]);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (input.trim() && !disabled) {
            onSend(input.trim());
            setInput('');
            if (textareaRef.current) {
                textareaRef.current.style.height = 'auto';
            }
        }
    };

    const handleKeyDown = (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSubmit(e);
        }
    };

    return (
        <form
            onSubmit={handleSubmit}
            style={{
                padding: '12px 16px',
                borderTop: '1px solid #e5e7eb',
                backgroundColor: '#ffffff',
                display: 'flex',
                gap: '8px',
                alignItems: 'flex-end',
                flexShrink: 0,
            }}
        >
      <textarea
          ref={textareaRef}
          value={input}
          onChange={(e) => setInput(e.target.value)}
          onKeyDown={handleKeyDown}
          placeholder="Type your question..."
          disabled={disabled}
          rows={1}
          style={{
              flex: 1,
              padding: '10px 12px',
              border: '1px solid #d1d5db',
              borderRadius: '8px',
              fontSize: '14px',
              outline: 'none',
              resize: 'none',
              fontFamily: 'inherit',
              lineHeight: '1.5',
              minHeight: '42px',
              maxHeight: '120px',
              backgroundColor: disabled ? '#f9fafb' : '#ffffff',
              cursor: disabled ? 'not-allowed' : 'text',
          }}
          onFocus={(e) => {
              e.target.style.borderColor = primaryColor;
          }}
          onBlur={(e) => {
              e.target.style.borderColor = '#d1d5db';
          }}
      />
            <button
                type="submit"
                disabled={disabled || !input.trim()}
                style={{
                    padding: '10px 20px',
                    backgroundColor: primaryColor,
                    color: '#ffffff',
                    border: 'none',
                    borderRadius: '8px',
                    fontSize: '14px',
                    fontWeight: 500,
                    cursor: disabled || !input.trim() ? 'not-allowed' : 'pointer',
                    opacity: disabled || !input.trim() ? 0.5 : 1,
                    transition: 'opacity 0.2s, transform 0.1s',
                    flexShrink: 0,
                    height: '42px',
                }}
                onMouseDown={(e) => {
                    if (!disabled && input.trim()) {
                        e.target.style.transform = 'scale(0.95)';
                    }
                }}
                onMouseUp={(e) => {
                    e.target.style.transform = 'scale(1)';
                }}
                onMouseLeave={(e) => {
                    e.target.style.transform = 'scale(1)';
                }}
            >
                Send
            </button>
        </form>
    );
}