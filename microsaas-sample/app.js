async function loadRuntime() {
    let runtime = {};

    try {
        if (window.__PORTAL_RUNTIME__) {
            runtime = window.__PORTAL_RUNTIME__;
        }

        const response = await fetch('./portal-runtime.json', { cache: 'no-store' });
        if (response.ok) {
            runtime = await response.json();
        }
    } catch (error) {
        console.error('Failed loading runtime config', error);
    }

    document.getElementById('app-name').textContent = runtime.name || 'Micro-SaaS Demo';
    document.getElementById('app-meta').textContent = runtime.backend_base_url
        ? `Backend aktif: ${runtime.backend_base_url}`
        : 'Backend URL belum tersedia.';
    document.getElementById('runtime-output').textContent = JSON.stringify(runtime, null, 2);

    return runtime;
}

let currentRuntime = null;

document.getElementById('ping-button').addEventListener('click', async () => {
    const target = currentRuntime?.backend_base_url;
    const result = document.getElementById('ping-result');

    if (!target) {
        result.textContent = 'backend_base_url belum ada.';
        return;
    }

    result.textContent = `Siap dipakai ke endpoint: ${target}`;
});

loadRuntime().then((runtime) => {
    currentRuntime = runtime;
});
