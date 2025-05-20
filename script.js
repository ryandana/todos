import 'animate.css';

document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      document.getElementById('search').focus();
    }
  });

  // const year = new Date().getFullYear();
  // document.getElementById('copyright').innerText = `Copyright © ${year} - All rights reserved`;

  // Apply theme from localStorage on page load
document.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
      document.documentElement.setAttribute("data-theme", savedTheme);

      // Set the radio button as checked
      const selectedInput = document.querySelector(
          `input.theme-controller[value="${savedTheme}"]`
      );
      if (selectedInput) {
          selectedInput.checked = true;
      }
  }

  // Attach change listeners to all theme controller radios
  document.querySelectorAll("input.theme-controller").forEach((input) => {
      input.addEventListener("change", (e) => {
          const theme = e.target.value;
          document.documentElement.setAttribute("data-theme", theme);
          localStorage.setItem("theme", theme);
      });
  });
});

