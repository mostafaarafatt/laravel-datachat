import React from 'react'
import ReactDOM from 'react-dom/client'
import ChatWidget from './components/ChatWidget'
import './styles.css'

// Global DataChat object
window.DataChat = {
    /**
     * Initialize the DataChat widget
     * @param {Object} config - Widget configuration
     * @param {string} config.apiKey - Your DataChat API key
     * @param {string} config.apiUrl - Your Laravel app URL
     * @param {string} config.userId - Optional user identifier
     * @param {Object} config.metadata - Optional metadata for scoping queries
     */
    init: function(config) {
        if (!config.apiKey) {
            console.error('DataChat: apiKey is required');
            return;
        }

        if (!config.apiUrl) {
            console.error('DataChat: apiUrl is required');
            return;
        }

        // Create or get container
        const container = document.getElementById('datachat-root') || this.createContainer();

        // Render widget
        const root = ReactDOM.createRoot(container);
        root.render(
            <React.StrictMode>
                <ChatWidget config={config} />
            </React.StrictMode>
        );

        console.log('DataChat initialized successfully');
    },

    /**
     * Create container element
     */
    createContainer: function() {
        const container = document.createElement('div');
        container.id = 'datachat-root';
        document.body.appendChild(container);
        return container;
    },

    /**
     * Destroy widget instance
     */
    destroy: function() {
        const container = document.getElementById('datachat-root');
        if (container) {
            container.remove();
        }
    }
};

// Auto-initialize if data attributes are present
document.addEventListener('DOMContentLoaded', () => {
    const autoInit = document.querySelector('[data-datachat-key]');
    if (autoInit) {
        window.DataChat.init({
            apiKey: autoInit.getAttribute('data-datachat-key'),
            apiUrl: autoInit.getAttribute('data-datachat-url') || window.location.origin,
            userId: autoInit.getAttribute('data-datachat-user-id'),
            metadata: autoInit.getAttribute('data-datachat-metadata')
                ? JSON.parse(autoInit.getAttribute('data-datachat-metadata'))
                : {}
        });
    }
});