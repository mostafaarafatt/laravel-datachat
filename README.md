# DataChat - AI-Powered Chat Widget for Laravel

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10%2B%20%7C%2011%2B-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/React-18-blue?style=for-the-badge&logo=react" alt="React">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

Transform your Laravel database into a conversational interface! DataChat allows users to ask questions in natural language and get instant insights from your data - no SQL knowledge required.

> **Built on top of [Laravel AI Database Assistant](https://github.com/mostafaarafatt/laravel-ai-database)** - Extends the powerful AI-to-SQL engine with an embeddable frontend widget.

---

## ✨ Features

- 🤖 **Natural Language Queries** - Ask questions in plain English
- 💬 **Embeddable Widget** - Drop-in chat interface for any website
- 🔒 **Secure by Default** - Row-level security, rate limiting, API key auth
- 🎨 **Fully Customizable** - Colors, position, suggestions, branding
- ⚡ **Real-time Responses** - Asynchronous processing with queues
- 📊 **Usage Analytics** - Track conversations, costs, and performance
- 🌐 **CORS Ready** - Easy cross-domain embedding
- 🔧 **Multi-AI Support** - Works with Anthropic Claude, OpenAI GPT-4, Google Gemini

---

## 📸 Screenshots

### Chat Widget
```
┌────────────────────────────────┐
│ DataChat           [×]         │
├────────────────────────────────┤
│                                │
│ 💡 Try asking:                 │
│ → How many users do we have?   │
│ → What is our revenue today?   │
│ → Show me recent orders        │
│                                │
├────────────────────────────────┤
│ [Type your question...]  [Send]│
└────────────────────────────────┘
```

### Dashboard
- Widget configuration & management
- Real-time analytics & usage graphs
- Conversation history viewer
- API key management

---

## 🚀 Quick Start

### Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x
- MySQL 5.7+ or PostgreSQL 10+
- Redis (recommended for queues)
- Node.js 18+ (for building widget)
- Composer 2.x

### Installation

#### Step 1: Install via Composer
```bash
composer require mostafaarafat/laravel-datachat
```

#### Step 2: Publish Configuration & Run Migrations
```bash
# Publish config file
php artisan vendor:publish --tag=datachat-config

# Run migrations
php artisan migrate

# Publish widget assets
php artisan vendor:publish --tag=datachat-assets
```

#### Step 3: Configure Your AI Provider

Add your AI API key to `.env`:
```env
# For Anthropic Claude (recommended)
ANTHROPIC_API_KEY=your_api_key_here

# OR for OpenAI
OPENAI_API_KEY=your_api_key_here

# OR for Google Gemini
GEMINI_API_KEY=your_api_key_here

# Choose your provider
DATACHAT_AI_PROVIDER=anthropic  # or 'openai' or 'gemini'
```

#### Step 4: Configure Queue (Important!)

DataChat processes messages asynchronously for better performance.
```env
# In .env
QUEUE_CONNECTION=redis  # or 'database'
```

Start the queue worker:
```bash
php artisan queue:work --queue=datachat
```

For production, use [Laravel Supervisor](https://laravel.com/docs/queues#supervisor-configuration).

#### Step 5: Create Your First Widget
```bash
php artisan datachat:install
```

This interactive command will:
- Create a widget configuration
- Generate an API key
- Set up default suggested questions
- Show installation code

---

## 📦 Usage

### Embed Widget in Your Website

Add this code to any HTML page:
```html
<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
</head>
<body>
    <!-- Your page content -->

    <!-- DataChat Widget -->
    <script src="https://your-app.com/vendor/datachat/datachat-widget.umd.js"></script>
    <link rel="stylesheet" href="https://your-app.com/vendor/datachat/datachat-widget.css">

    <script>
      DataChat.init({
        apiKey: 'dc_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        apiUrl: 'https://your-app.com'
      });
    </script>
</body>
</html>
```

### Advanced Configuration
```html
<script>
  DataChat.init({
    apiKey: 'dc_your_api_key_here',
    apiUrl: 'https://your-app.com',
    
    // Optional: User identification
    userId: '{{ auth()->id() }}',
    
    // Optional: Metadata for row-level security
    metadata: {
      customer_id: '123',
      plan_type: 'premium',
      tenant_id: 'acme_corp'
    }
  });
</script>
```

### Laravel Blade Integration
```blade
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard</h1>
        <!-- Your dashboard content -->
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/datachat/datachat-widget.umd.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/datachat/datachat-widget.css') }}">

    <script>
      DataChat.init({
        apiKey: '{{ config('datachat.demo_key') }}', // Store in config
        apiUrl: '{{ config('app.url') }}',
        userId: '{{ auth()->id() }}',
        metadata: {
          role: '{{ auth()->user()->role }}',
          company_id: '{{ auth()->user()->company_id }}'
        }
      });
    </script>
@endpush
```

---

## 🎛️ Dashboard Management

Access the dashboard to manage your widgets:
```
https://your-app.com/datachat
```

**Note:** Dashboard requires authentication. Ensure users are logged in.

### Dashboard Features

- **Widget List** - View all configured widgets
- **Create Widget** - Set up new chat interfaces
- **Analytics** - View usage stats and message volume
- **API Keys** - Generate and regenerate keys
- **Conversations** - Browse chat history
- **Suggested Questions** - Configure helpful prompts

---

## ⚙️ Configuration

Edit `config/datachat.php`:
```php
return [
    // AI Provider
    'ai_provider' => env('DATACHAT_AI_PROVIDER', 'anthropic'),

    // Database connection
    'connection' => env('DATACHAT_DB_CONNECTION', null),

    // Security
    'strict_mode' => env('DATACHAT_STRICT_MODE', true),

    // Queue settings
    'queue' => [
        'enabled' => env('DATACHAT_QUEUE_ENABLED', true),
        'connection' => env('DATACHAT_QUEUE_CONNECTION', 'redis'),
        'queue_name' => env('DATACHAT_QUEUE_NAME', 'datachat'),
    ],

    // Cache
    'cache' => [
        'enabled' => env('DATACHAT_CACHE_ENABLED', true),
        'ttl' => env('DATACHAT_CACHE_TTL', 3600),
    ],

    // Rate limiting
    'rate_limits' => [
        'messages_per_day' => env('DATACHAT_MAX_MESSAGES_PER_DAY', 100),
        'messages_per_minute' => env('DATACHAT_MAX_MESSAGES_PER_MINUTE', 10),
    ],

    // Widget defaults
    'widget_defaults' => [
        'name' => 'DataChat',
        'primary_color' => '#3b82f6',
        'position' => 'bottom-right',
        'greeting_message' => 'Hi! Ask me anything about your data.',
    ],

    // CORS
    'cors' => [
        'allowed_origins' => env('DATACHAT_ALLOWED_ORIGINS', '*'),
        'allowed_methods' => ['GET', 'POST', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'X-DataChat-Key', 'X-Requested-With'],
    ],
];
```

---

## 🔒 Security Features

### API Key Authentication

Every widget has a unique API key. Keys can be regenerated anytime:
```bash
php artisan datachat:generate-key {widget_id}
```

Or via dashboard: Widget Details → "Regenerate Key"

### Row-Level Security (User Scoping)

Restrict queries to user-specific data using metadata:
```javascript
DataChat.init({
  apiKey: 'dc_xxx',
  apiUrl: 'https://app.com',
  metadata: {
    user_id: '123',      // Only show this user's data
    company_id: '456'    // Scope to company
  }
});
```

**Backend Implementation:**

Modify `ChatService` to apply scoping:
```php
protected function applyScopingRules(array $metadata): void
{
    if (isset($metadata['user_id'])) {
        DB::table('orders')->where('user_id', $metadata['user_id']);
    }
}
```

### Rate Limiting

Configure per-widget limits:

- **Daily limit** - Total messages per 24 hours
- **Per-minute limit** - Prevents spam/abuse

Users receive clear error messages when limits are reached.

### CORS Protection

Restrict widget usage to specific domains:
```php
// In widget configuration
$widget->update([
    'allowed_domains' => ['example.com', 'app.example.com']
]);
```

Requests from other domains will be rejected.

### Strict Mode

When enabled (default), only `SELECT` queries are allowed. No `INSERT`, `UPDATE`, `DELETE`, or `DROP`.
```env
DATACHAT_STRICT_MODE=true
```

---

## 🎨 Customization

### Widget Appearance

Configure via dashboard or programmatically:
```php
use Mostafaarafat\DataChat\Models\ChatConfig;

$widget = ChatConfig::create([
    'user_id' => auth()->id(),
    'widget_name' => 'Sales Assistant',
    'primary_color' => '#ff6b6b',  // Brand color
    'position' => 'bottom-left',   // or 'bottom-right'
    'greeting_message' => 'Hello! I can help you find sales data.',
    'max_messages_per_day' => 500,
]);
```

### Suggested Questions

Add helpful prompts to guide users:
```php
use Mostafaarafat\DataChat\Models\Suggestion;

Suggestion::create([
    'config_id' => $widget->id,
    'question' => 'Show me today\'s revenue',
    'category' => 'sales',
    'display_order' => 1,
]);
```

### Custom Styling

Override CSS variables:
```html
<style>
  :root {
    --datachat-primary: #your-color;
    --datachat-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
</style>
```

---

## 📊 Analytics & Monitoring

### Usage Tracking

DataChat automatically tracks:

- Message count per day
- Estimated AI API costs
- Active conversations
- Processing times

Access via dashboard or query directly:
```php
use Mostafaarafat\DataChat\Models\Usage;

$usage = Usage::where('config_id', $widgetId)
    ->where('date', '>=', now()->subDays(30))
    ->get();

$totalMessages = $usage->sum('message_count');
$totalCost = $usage->sum('ai_api_cost');
```

### Conversation History

View all conversations:
```php
use Mostafaarafat\DataChat\Models\Conversation;

$conversations = Conversation::where('config_id', $widgetId)
    ->with('messages')
    ->latest('last_message_at')
    ->get();
```

### Logs

All errors are logged to Laravel's log system:
```bash
tail -f storage/logs/laravel.log | grep DataChat
```

---

## 🧪 Testing

### Test Widget Locally

Create a test HTML file:
```html
<!DOCTYPE html>
<html>
<head>
    <title>DataChat Test</title>
</head>
<body>
    <h1>DataChat Widget Test</h1>
    
    <script src="http://localhost:8000/vendor/datachat/datachat-widget.umd.js"></script>
    <link rel="stylesheet" href="http://localhost:8000/vendor/datachat/datachat-widget.css">

    <script>
      DataChat.init({
        apiKey: 'dc_your_test_key',
        apiUrl: 'http://localhost:8000'
      });
    </script>
</body>
</html>
```

### Test API Endpoints
```bash
# Test configuration endpoint
curl -X GET http://localhost:8000/api/datachat/config \
  -H "X-DataChat-Key: dc_your_api_key"

# Test sending a message
curl -X POST http://localhost:8000/api/datachat/message \
  -H "X-DataChat-Key: dc_your_api_key" \
  -H "Content-Type: application/json" \
  -d '{
    "message": "How many users are there?",
    "session_id": "test_session_123"
  }'
```

### Automated Tests

Run package tests:
```bash
composer test
```

---

## 🐛 Troubleshooting

### Widget Not Loading

**Problem:** Chat button doesn't appear

**Solutions:**
1. Check browser console for errors
2. Verify API key is correct
3. Ensure JavaScript files are loaded
4. Check CORS settings if embedding on external domain
```bash
# Check if files exist
ls -la public/vendor/datachat/

# If missing, republish:
php artisan vendor:publish --tag=datachat-assets --force
```

### Messages Not Sending

**Problem:** Messages appear but no response

**Solutions:**
1. Verify queue worker is running
2. Check Laravel logs for errors
3. Confirm AI API key is valid
```bash
# Check queue status
php artisan queue:work --queue=datachat --once

# Check logs
tail -f storage/logs/laravel.log
```

### API Key Invalid Error

**Problem:** 401 Unauthorized errors

**Solutions:**
1. Regenerate API key via dashboard
2. Clear browser cache
3. Verify key matches widget configuration
```bash
php artisan datachat:generate-key {widget_id}
```

### CORS Errors

**Problem:** "CORS policy: No 'Access-Control-Allow-Origin' header"

**Solutions:**
1. Add domain to allowed_domains in widget settings
2. Check config/datachat.php CORS settings
3. Verify middleware is applied
```php
// In widget configuration
$widget->update([
    'allowed_domains' => ['your-domain.com']
]);
```

### Queue Not Processing

**Problem:** Messages stuck in "processing" state

**Solutions:**
1. Restart queue worker
2. Check Redis connection
3. Review failed jobs
```bash
# Restart queue
php artisan queue:restart

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Widget Build Errors

**Problem:** `npm run build` fails

**Solutions:**
1. Use Node 18+
2. Clear npm cache
3. Delete node_modules and reinstall
```bash
# Check Node version
node -v  # Should be 18+

# Clean install
rm -rf node_modules package-lock.json
npm install
npm run build
```

---

## 🚀 Production Deployment

### 1. Environment Variables
```env
# Production .env
APP_ENV=production
APP_DEBUG=false

# Queue
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_secure_password
REDIS_PORT=6379

# AI Provider
ANTHROPIC_API_KEY=your_production_key
DATACHAT_AI_PROVIDER=anthropic

# Security
DATACHAT_STRICT_MODE=true
DATACHAT_ALLOWED_ORIGINS=https://yourdomain.com
```

### 2. Build Widget
```bash
cd resources/js/widget
npm install --production
npm run build
```

### 3. Optimize Laravel
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Set Up Supervisor

Create `/etc/supervisor/conf.d/datachat-worker.conf`:
```ini
[program:datachat-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/app/artisan queue:work redis --queue=datachat --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/worker.log
stopwaitsecs=3600
```

Reload Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start datachat-worker:*
```

### 5. CDN (Optional)

For better performance, serve widget files via CDN:
```bash
# Upload to S3/CloudFront
aws s3 cp public/vendor/datachat/ s3://your-bucket/datachat/ --recursive
```

Update widget initialization:
```html
<script src="https://cdn.yourdomain.com/datachat/datachat-widget.umd.js"></script>
```

### 6. SSL/HTTPS

Ensure your app is served over HTTPS for security.

### 7. Monitoring

Set up error tracking (e.g., Sentry, Bugsnag):
```bash
composer require sentry/sentry-laravel
```

---

## 📈 Performance Optimization

### Caching

Enable caching for repeated queries:
```env
DATACHAT_CACHE_ENABLED=true
DATACHAT_CACHE_TTL=3600  # 1 hour
```

### Database Indexing

Add indexes for frequently queried columns:
```sql
CREATE INDEX idx_conversations_last_message ON datachat_conversations(last_message_at);
CREATE INDEX idx_messages_created ON datachat_messages(created_at);
CREATE INDEX idx_usage_config_date ON datachat_usage(config_id, date);
```

### Queue Optimization

Use multiple workers for high traffic:
```bash
# In supervisor config
numprocs=4  # Run 4 workers
```

### Redis Optimization

Configure Redis for queue performance:
```bash
# redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

---

## 🔌 API Reference

### Configuration Endpoint
```http
GET /api/datachat/config
Headers:
  X-DataChat-Key: dc_your_api_key

Response:
{
  "name": "DataChat",
  "primary_color": "#3b82f6",
  "position": "bottom-right",
  "greeting_message": "Hi! Ask me anything.",
  "suggestions": [
    "How many users?",
    "Show revenue"
  ]
}
```

### Send Message
```http
POST /api/datachat/message
Headers:
  X-DataChat-Key: dc_your_api_key
  Content-Type: application/json

Body:
{
  "message": "How many orders today?",
  "session_id": "session_abc123",
  "user_id": "user_456",
  "metadata": {
    "customer_id": "789"
  }
}

Response:
{
  "status": "processing",
  "conversation_id": 42
}
```

### Get Conversation
```http
GET /api/datachat/conversation/{id}
Headers:
  X-DataChat-Key: dc_your_api_key

Response:
{
  "messages": [
    {
      "id": 1,
      "role": "user",
      "content": "How many orders today?",
      "created_at": "2025-03-30T10:30:00Z",
      "has_error": false
    },
    {
      "id": 2,
      "role": "assistant",
      "content": "You have 47 orders today.",
      "created_at": "2025-03-30T10:30:03Z",
      "has_error": false
    }
  ]
}
```

### Poll for New Messages
```http
GET /api/datachat/conversation/{id}/poll?after_id=2
Headers:
  X-DataChat-Key: dc_your_api_key

Response:
{
  "messages": [
    {
      "id": 3,
      "role": "assistant",
      "content": "Latest response...",
      "created_at": "2025-03-30T10:31:00Z",
      "has_error": false
    }
  ]
}
```

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Development Setup
```bash
# Clone repository
git clone https://github.com/mostafaarafatt/laravel-datachat.git
cd laravel-datachat

# Install dependencies
composer install
cd resources/js/widget
npm install

# Run tests
composer test
npm test
```

### Coding Standards

- Follow PSR-12 for PHP code
- Use ESLint for JavaScript
- Write tests for new features
- Update documentation

---

## 📝 Changelog

### [1.0.0] - 2025-03-30

#### Added
- Initial release
- AI-powered chat widget
- Dashboard for widget management
- Real-time message polling
- Usage analytics & tracking
- Multi-AI provider support
- CORS & security features
- Queue-based processing
- Customizable appearance
- Suggested questions
- Rate limiting

---

## 🛣️ Roadmap

### v1.1 (Q2 2025)
- [ ] WebSocket support for real-time updates
- [ ] Voice input integration
- [ ] Export conversation to PDF/CSV
- [ ] Advanced analytics dashboard
- [ ] Multi-language support

### v1.2 (Q3 2025)
- [ ] Team collaboration features
- [ ] Slack/Discord bot integration
- [ ] Custom AI model training
- [ ] White-label option
- [ ] Mobile SDK (iOS/Android)

### v2.0 (Q4 2025)
- [ ] GraphQL support
- [ ] Workflow automation
- [ ] Advanced user permissions
- [ ] Data visualization in chat
- [ ] Plugin system

---

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

---

## 🙏 Credits

Built by **[Mostafa Arafat](https://github.com/mostafaarafatt)**

Powered by:
- [Laravel AI Database Assistant](https://github.com/mostafaarafatt/laravel-ai-database)
- [Anthropic Claude](https://www.anthropic.com)
- [Laravel Framework](https://laravel.com)
- [React](https://react.dev)

---

## 💬 Support

- **Documentation:** [https://docs.datachat.dev](https://docs.datachat.dev)
- **Issues:** [GitHub Issues](https://github.com/mostafaarafatt/laravel-datachat/issues)
- **Discussions:** [GitHub Discussions](https://github.com/mostafaarafatt/laravel-datachat/discussions)
- **Email:** support@datachat.dev
- **Twitter:** [@datachat](https://twitter.com/datachat)

---

## ⭐ Show Your Support

If you find this package helpful, please consider:

- ⭐ Starring the repository
- 🐛 Reporting bugs
- 💡 Suggesting features
- 📝 Writing tutorials
- 💰 [Sponsoring development](https://github.com/sponsors/mostafaarafatt)

---

<p align="center">Made with ❤️ for the Laravel community</p>
