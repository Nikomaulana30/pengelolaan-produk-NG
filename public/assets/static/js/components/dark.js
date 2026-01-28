
const THEME_KEY = "theme"

function toggleDarkTheme() {
  setTheme(
    document.documentElement.getAttribute("data-bs-theme") === 'dark'
      ? "light"
      : "dark"
  )
}

/**
 * Set theme for mazer
 * @param {"dark"|"light"} theme
 * @param {boolean} persist 
 */
function setTheme(theme, persist = false) {
  document.body.classList.remove('light', 'dark')
  document.body.classList.add(theme)
  document.documentElement.setAttribute('data-bs-theme', theme)
  document.body.setAttribute('data-bs-theme', theme)
  document.documentElement.style.colorScheme = theme
  
  // Apply theme to sidebar
  const sidebar = document.getElementById('sidebar')
  if (sidebar) {
    sidebar.setAttribute('data-bs-theme', theme)
  }
  
  // Apply theme to main content
  const main = document.getElementById('main')
  if (main) {
    main.setAttribute('data-bs-theme', theme)
  }
  
  if (persist) {
    localStorage.setItem(THEME_KEY, theme)
  }
}

/**
 * Init theme from localStorage or system preference
 */
function initTheme() {
  // First priority: check localStorage (user's last preference)
  const storedTheme = localStorage.getItem(THEME_KEY)
  if (storedTheme && (storedTheme === 'dark' || storedTheme === 'light')) {
    return setTheme(storedTheme, false)
  }
  
  // Second priority: check body data-bs-theme from server (from database)
  const bodyTheme = document.body.getAttribute('data-bs-theme')
  if (bodyTheme && (bodyTheme === 'dark' || bodyTheme === 'light')) {
    return setTheme(bodyTheme, true) // Save to localStorage to persist user choice
  }
  
  // Finally detect system preference
  if (!window.matchMedia) {
    return setTheme('light', true)
  }

  const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)")
  mediaQuery.addEventListener("change", (e) =>
    setTheme(e.matches ? "dark" : "light", true)
  )
  return setTheme(mediaQuery.matches ? "dark" : "light", true)
}

// Initialize theme FIRST before setting up toggle
initTheme()

window.addEventListener('DOMContentLoaded', () => {
  const toggler = document.getElementById("toggle-dark")
  
  if(toggler) {
    // Get current theme from DOM (after initTheme has applied it)
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light'
    
    // Sync checkbox with current theme
    toggler.checked = currentTheme === "dark"
    
    // Setup toggle listener
    toggler.addEventListener("input", (e) => {
      setTheme(e.target.checked ? "dark" : "light", true)
    })
  }
});
