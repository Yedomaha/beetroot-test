export default () => {

    setMarginBottomToMain();
    window.addEventListener("resize", function() {
        setMarginBottomToMain();
    });

    function setMarginBottomToMain() {
        console.log('test');
        const main = document.getElementById('main');
        const footer = document.getElementById('footer');
        if (!main || !footer) return;
        console.log('test 1');
        let footerHeight = footer.clientHeight;
        console.log(footerHeight);
        main.style.cssText = `margin-bottom:${footerHeight}px`;
    }
}