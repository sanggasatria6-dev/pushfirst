<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runtime Config Example</title>
</head>
<body>
    <script src="/portal-runtime.js"></script>
    <script>
        const runtime = window.__PORTAL_RUNTIME__ || {};

        async function boot() {
            const response = await fetch('/portal-runtime.json');
            const json = await response.json();
            const backendBaseUrl = json.backend_base_url || runtime.backend_base_url;
            console.log('Backend URL:', backendBaseUrl);
        }

        boot();
    </script>
</body>
</html>
