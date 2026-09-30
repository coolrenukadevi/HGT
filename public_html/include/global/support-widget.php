<?php require_once __DIR__ . '/../site_config.php'; ?>
<!-- Floating support widget: 1 Enquire Now (opens the Enquire Now dialog), 2 Chat with us (WhatsApp / call inside), 3 Email us (owner order, 2026-09-30).
     Call and WhatsApp are not listed separately: they are in "Chat with us" and in the header. Behaviour: assets/js/hg-site.js -->
<div class="hg-support" id="hg-support">
    <div class="hg-support__panel" id="hg-support-panel" role="dialog" aria-labelledby="hg-support-title" hidden>
        <div class="hg-support__head">
            <div>
                <p class="hg-support__title" id="hg-support-title">How can we help?</p>
                <p class="hg-support__sub">Talk to a Holiday Guru travel expert</p>
            </div>
            <button type="button" class="hg-support__close" data-hg-support-close aria-label="Close contact options">
                <?= hg_icon('close') ?>
            </button>
        </div>

        <div class="hg-support__view" data-hg-view="actions">
            <button type="button" class="hg-support__action" data-hg-enquiry-open aria-haspopup="dialog" data-hg-track="enquiry_start">
                <span class="hg-support__icon" aria-hidden="true"><?= hg_icon('route') ?></span>
                <span class="hg-support__label">Enquire Now<small>Get an itinerary and quote</small></span>
            </button>
            <button type="button" class="hg-support__action" data-hg-chat-open>
                <span class="hg-support__icon" aria-hidden="true"><?= hg_icon('chat') ?></span>
                <span class="hg-support__label">Chat with us<small>WhatsApp or call a travel expert</small></span>
            </button>
            <a class="hg-support__action" href="<?= hg_e(hg_mailto_href()) ?>" data-hg-track="email">
                <span class="hg-support__icon" aria-hidden="true"><?= hg_icon('mail') ?></span>
                <span class="hg-support__label">Email us<small><?= hg_e(HG_EMAIL_DISPLAY) ?></small></span>
            </a>
        </div>

        <div class="hg-support__view" data-hg-view="chat" hidden>
            <!--
                CHATBOT INTEGRATION POINT
                No chatbot or live-chat backend is connected yet. When one is approved,
                mount its client here (or replace this block) and remove the notice below.
                Do not add scripted/fake replies.
            -->
            <p class="hg-support__notice"><strong>Live chat is not available yet.</strong> For the fastest reply, message a travel expert on WhatsApp or call us.</p>
            <a class="hg-support__btn hg-support__btn--primary" href="<?= hg_e(hg_whatsapp_href()) ?>" target="_blank" rel="noopener" data-hg-whatsapp data-hg-track="whatsapp">
                <?= hg_icon('whatsapp') ?> Chat on WhatsApp
            </a>
            <a class="hg-support__btn" href="<?= hg_e(hg_tel_href()) ?>" data-hg-track="call">
                <?= hg_icon('phone') ?> Call Holiday Guru
            </a>
            <button type="button" class="hg-support__back" data-hg-chat-back>&larr; All contact options</button>
        </div>
    </div>

    <button type="button" class="hg-support__toggle" aria-expanded="false" aria-controls="hg-support-panel" aria-label="Contact Holiday Guru Travel">
        <?= hg_icon('chat', 'hg-support__toggle-open') ?>
        <?= hg_icon('close', 'hg-support__toggle-close') ?>
        <span class="hg-support__toggle-text">Need help?</span>
    </button>
</div>
