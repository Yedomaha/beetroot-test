export default function() {
  const pageName = document.body.getAttribute('data-page-name');
  if (!pageName) return;

  const pageList = {
  };

  if (pageName && pageList[pageName]) pageList[pageName]();
}
