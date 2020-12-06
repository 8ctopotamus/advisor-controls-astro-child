(function() {
  const tabs = Array.from(document.querySelectorAll('.tab-link'));
  const contents = Array.from(document.querySelectorAll('.tab-content'));

  const handleTabClick = e => {
    e.preventDefault();
    [...tabs, ...contents].forEach(t => {
      const target = e.target.getAttribute('href');
      t.classList.remove('active');
      const elVal = t.tagName === 'A' 
        ? t.getAttribute('href')
        : t.id;
      if (target.includes(elVal)) t.classList.add('active');
    });
  };

  tabs.forEach(t => t.addEventListener('click', handleTabClick));
})();