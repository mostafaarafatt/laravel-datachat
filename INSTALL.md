# DataChat Installation Guide

## Step 1: Install Package in Your Laravel App
```bash
cd /path/to/your/laravel/project

# Add local package to composer.json
composer config repositories.datachat path "./packages/mostafaarafat/laravel-datachat"

# Require the package
composer require mostafaarafat/laravel-datachat @dev
```

## Step 2: Run Migrations
```bash
php artisan migrate
```

## Step 3: Build Frontend Widget
```bash
cd packages/mostafaarafat/laravel-datachat/resources/js/widget

# Install dependencies
npm install

# Build widget
npm run build
```

This creates `public/datachat-widget.umd.js` and `public/datachat-widget.css`

## Step 4: Copy Widget to Public Directory
```bash
# From Laravel root
cp packages/mostafaarafat/laravel-datachat/public/* public/vendor/datachat/
```

Or publish via Artisan:
```bash
php artisan vendor:publish --tag=datachat-assets
```

## Step 5: Configure Queue

In `.env`:
```env
QUEUE_CONNECTION=redis  # or database
```

Run queue worker:
```bash
php artisan queue:work --queue=datachat
```

## Step 6: Create Your First Widget

Visit: `http://yourapp.test/datachat`

Or via Artisan:
```bash
php artisan datachat:install
```

## Step 7: Embed Widget

Add to your HTML:
```html
<script src="{{ asset('vendor/datachat/datachat-widget.umd.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/datachat/datachat-widget.css') }}">

<script>
  DataChat.init({
    apiKey: 'YOUR_API_KEY_HERE',
    apiUrl: '{{ config("app.url") }}',
    userId: '{{ auth()->id() }}',  // Optional
    metadata: {
      // Optional metadata for scoping
      customer_id: '123',
      plan: 'pro'
    }
  });
</script>
```

## Troubleshooting

### Widget Not Loading
- Check browser console for errors
- Verify API key is correct
- Check CORS settings in config/datachat.php

### Messages Not Sending
- Ensure queue worker is running
- Check `datachat_messages` table
- Review Laravel logs: `tail -f storage/logs/laravel.log`

### Build Errors
- Node version: Requires Node 18+
- Clear npm cache: `npm cache clean --force`
- Delete node_modules and reinstall

## Production Deployment

1. Build widget: `npm run build`
2. Publish assets: `php artisan vendor:publish --tag=datachat-assets --force`
3. Run migrations: `php artisan migrate --force`
4. Start queue worker as daemon
5. Configure allowed domains in widget settings
