if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('/sw.js');
    });
  }
  
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    e.prompt();
  });