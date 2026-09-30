    /* ---------------- Login dialog ---------------- */
    var login = document.getElementById('hg-login');
    $$('[data-hg-login-open]').forEach(function (b) {
        b.addEventListener('click', function () {
            if (!login) return;
            if (nav && nav.classList.contains('is-open')) setDrawer(false);
            if (typeof login.showModal === 'function') login.showModal(); else login.setAttribute('open', '');
        });
    });
    if (login) login.addEventListener('click', function (e) { if (e.target === login) login.close(); });


    /* ---------------- Utility bar Login menu ---------------- */
    $$('[data-hg-umenu]').forEach(function (menu) {
        var btn = $('.hg-umenu__btn', menu), list = $('.hg-umenu__list', menu);
        if (!btn || !list) return;
        var set = function (open) { btn.setAttribute('aria-expanded', open ? 'true' : 'false'); list.hidden = !open; };
        btn.addEventListener('click', function () { set(list.hidden); });
        list.addEventListener('click', function (e) { if (e.target.closest('a, button')) set(false); });
        document.addEventListener('click', function (e) { if (!menu.contains(e.target)) set(false); });
        menu.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !list.hidden) { set(false); btn.focus(); } });
    });

    /* ---------------- "Enquire Now" dialog (menu tab) ---------------- */
    var enquiry = document.getElementById('hg-enquiry');
    $$('[data-hg-enquiry-open]').forEach(function (b) {
        b.addEventListener('click', function () {
            if (!enquiry) return;
            if (nav && nav.classList.contains('is-open')) setDrawer(false);
            var sp = document.querySelector('.hg-support__panel:not([hidden]) [data-hg-support-close]');
            if (sp) sp.click(); // close the "Need help?" panel first
            if (typeof enquiry.showModal === 'function') enquiry.showModal(); else enquiry.setAttribute('open', '');
            var first = enquiry.querySelector('input:not([type="hidden"])');
            if (first) first.focus();
        });
    });
    if (enquiry) enquiry.addEventListener('click', function (e) { if (e.target === enquiry) enquiry.close(); });
