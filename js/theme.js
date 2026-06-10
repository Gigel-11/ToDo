(function(){
  const themes = {
    dark: {
      '--bg': '#0f1120',
      '--surface': '#141622',
      '--card': '#1a1d2e',
      '--border': '#232642',
      '--border2': '#2e3250',
      '--accent': '#6c8ef5',
      '--accent2': '#8faaff',
      '--green': '#3dbf7c',
      '--red': '#d96a6a',
      '--orange': '#e08c3b',
      '--yellow': '#c8a840',
      '--text': '#dce0f5',
      '--muted': '#9098b8',
      '--dim': '#565d80'
    },
    light: {
      '--bg': '#f6f8ff',
      '--surface': '#ffffff',
      '--card': '#ffffff',
      '--border': '#e6e9f6',
      '--border2': '#eef1fb',
      '--accent': '#3855f6',
      '--accent2': '#5b75ff',
      '--green': '#1e9a53',
      '--red': '#c33a3a',
      '--orange': '#d9862a',
      '--yellow': '#b58d1f',
      '--text': '#102125',
      '--muted': '#6b7280',
      '--dim': '#4b5563'
    }
  };

  function applyTheme(name){
    const t = themes[name] || themes.dark;
    Object.keys(t).forEach(k => document.documentElement.style.setProperty(k, t[k]));
    document.documentElement.setAttribute('data-theme', name);
    localStorage.setItem('theme', name);
    const btn = document.getElementById('themeToggle');
    if (btn) {
      btn.textContent = name === 'dark' ? '☀️' : '🌙';
      btn.title = name === 'dark' ? 'Comută la tema deschisă' : 'Comută la tema închisă';
    }
  }

  function toggle(){
    const cur = localStorage.getItem('theme') || (document.documentElement.getAttribute('data-theme') || 'dark');
    applyTheme(cur === 'dark' ? 'light' : 'dark');
  }

  document.addEventListener('DOMContentLoaded', function(){
    const stored = localStorage.getItem('theme') || 'dark';
    applyTheme(stored);
    const btn = document.getElementById('themeToggle');
    if (btn) btn.addEventListener('click', toggle);
  });
})();
