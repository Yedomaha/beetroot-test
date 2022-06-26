export default () => {

    setMarginBottomToMain();
    window.addEventListener("resize", function() {
        setMarginBottomToMain();
    });

    function setMarginBottomToMain() {
        const main = document.getElementById('main');
        const footer = document.getElementById('footer');
        if (!main || !footer) return;
        let footerHeight = footer.clientHeight;
        main.style.cssText = `margin-bottom:${footerHeight}px`;
    }

}