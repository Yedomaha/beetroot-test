export default (gsap) => {
    let links = document.querySelectorAll('[data-anchor]');
    let header = document.querySelector('.header');
    const scollSpeed = 2600;
    const maxScrollTime = 1.3;
    if (links.length && header) {
        links.forEach(function(link) {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                let selector = link.getAttribute('href');
                let block = document.querySelector(selector);
                if (block) {
                    let offset = {};
                    let elOffsetY = block.getBoundingClientRect().top;
                    let headerHeight = header.offsetHeight;
                    offset.val = (document.documentElement && document.documentElement.scrollTop) ||
                        document.body.scrollTop || window.scrollY || window.pageYOffset;
                    let distance = elOffsetY - headerHeight + offset.val;
                    let time = Math.min(Math.abs(distance / scollSpeed), maxScrollTime);
                    gsap.to(offset, {
                        val: (elOffsetY - headerHeight + offset.val),
                        duration: time,
                        onUpdate: () => {
                            window.scrollTo(0, offset.val)
                        }
                    });
                    // window.scrollTo({
                    //     behavior: 'smooth',
                    //     top: (elOffsetY - headerHeight + offset.val),
                    // });
                }
            });
        });
    }
}
