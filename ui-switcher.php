<?php
/**
 * UI Switcher — Client Preview Panel
 *
 * A self-contained UI option switcher for client review. Shows a gear icon
 * (fixed, bottom-left) that opens a slide-out drawer with per-category toggles.
 * Activated by visiting the site with ?ui-preview=1 in the URL. Selection persists
 * across page navigation via sessionStorage and per-option via localStorage.
 *
 * HOW TO USE ON A NEW PROJECT:
 * 1. Copy this file into your theme's includes/ folder
 * 2. In functions.php, add: require_once FL_CHILD_THEME_DIR . '/includes/ui-switcher.php';
 * 3. Edit the $ui_switcher_config array below
 * 4. Write SCSS for each option scoped to html.{value}
 *    e.g. html.btn-hover-1 body .fl-page a.fl-button:hover { ... }
 */

// =============================================================================
// CONFIG — Edit this section to add/remove option groups
// =============================================================================

$ui_switcher_config = [
    [
        'id'      => 'btn-hover',
        'label'   => 'Button Hover Effect',
        'options' => [
            ['value' => 'btn-hover-1', 'label' => 'Option 1: Scale'],
            ['value' => 'btn-hover-2', 'label' => 'Option 2: Darken'],
        ],
        'default' => 'btn-hover-1',
    ],
    // Add more groups here — copy the block above:
    // [
    //     'id'      => 'nav-layout',
    //     'label'   => 'Navigation Layout',
    //     'options' => [
    //         ['value' => 'nav-layout-1', 'label' => 'Option 1'],
    //         ['value' => 'nav-layout-2', 'label' => 'Option 2'],
    //     ],
    //     'default' => 'nav-layout-1',
    // ],
];

// =============================================================================
// HOOKS — No need to edit below this line
// =============================================================================

add_action( 'wp_head',   'ui_switcher_head'   );
add_action( 'wp_footer', 'ui_switcher_footer' );

function ui_switcher_head() {
    global $ui_switcher_config;
    $config_json = wp_json_encode( $ui_switcher_config );
    ?>
    <style id="ui-switcher-styles">
        /* --- UI Switcher Panel --- */
        #ui-switcher-wrap { display: none; }
        #ui-switcher-wrap.uis-active { display: block; }

        #ui-switcher-trigger {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 9999;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #1a1a1a;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.35);
            transition: background 0.2s, transform 0.2s;
            padding: 0;
        }
        #ui-switcher-trigger:hover {
            background: #444;
            transform: rotate(30deg);
        }
        #ui-switcher-trigger svg {
            width: 20px;
            height: 20px;
            fill: #fff;
            pointer-events: none;
        }

        #ui-switcher-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.25);
            z-index: 9997;
        }
        #ui-switcher-overlay.uis-open { display: block; }

        #ui-switcher-drawer {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: min(300px, 85vw);
            background: #fff;
            z-index: 9998;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        #ui-switcher-drawer.uis-open { transform: translateX(0); }

        .uis-drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #eee;
            flex-shrink: 0;
        }
        .uis-drawer-header h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #111;
            letter-spacing: 0.01em;
        }
        #ui-switcher-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #888;
            font-size: 22px;
            line-height: 1;
            display: flex;
            align-items: center;
        }
        #ui-switcher-close:hover { color: #111; }

        .uis-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .uis-group { margin-bottom: 24px; }
        .uis-group:last-child { margin-bottom: 0; }

        .uis-group-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #999;
            margin-bottom: 10px;
        }

        .uis-options { display: flex; gap: 8px; }

        .uis-option {
            flex: 1;
            padding: 9px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            color: #444;
            transition: border-color 0.15s, background 0.15s, color 0.15s;
            white-space: nowrap;
        }
        .uis-option:hover { border-color: #aaa; }
        .uis-option.uis-selected {
            border-color: #1a1a1a;
            background: #1a1a1a;
            color: #fff;
        }
    </style>
    <script>
    // Flash prevention: apply saved option classes to <html> before render
    (function() {
        var config = <?= $config_json; ?>;
        config.forEach(function(g) {
            var saved = localStorage.getItem('uis-' + g.id) || g.default;
            document.documentElement.classList.add(saved);
        });
    })();
    </script>
    <?php
}

function ui_switcher_footer() {
    global $ui_switcher_config;
    $config_json = wp_json_encode( $ui_switcher_config );
    ?>
    <div id="ui-switcher-wrap">

        <button id="ui-switcher-trigger" aria-label="UI Options">
            <!-- Gear icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
        </button>

        <div id="ui-switcher-overlay"></div>

        <div id="ui-switcher-drawer" role="dialog" aria-label="UI Options">
            <div class="uis-drawer-header">
                <h3>UI Options</h3>
                <button id="ui-switcher-close" aria-label="Close">&#x2715;</button>
            </div>
            <div class="uis-drawer-body">
                <?php foreach ( $ui_switcher_config as $group ) : ?>
                <div class="uis-group">
                    <div class="uis-group-label"><?= esc_html( $group['label'] ) ?></div>
                    <div class="uis-options">
                        <?php foreach ( $group['options'] as $option ) : ?>
                        <button
                            class="uis-option"
                            data-group="<?= esc_attr( $group['id'] ) ?>"
                            data-value="<?= esc_attr( $option['value'] ) ?>"
                        ><?= esc_html( $option['label'] ) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <script>
    (function() {
        var config  = <?= $config_json; ?>;
        var PREFIX  = 'uis-';
        var el      = document.documentElement; // classes live on <html>

        // Show panel when ?ui-preview=1 is in URL, persist via sessionStorage
        var params = new URLSearchParams(window.location.search);
        if (params.has('ui-preview')) {
            sessionStorage.setItem('uis-preview', '1');
        }
        var isPreview = sessionStorage.getItem('uis-preview') === '1';

        var wrap     = document.getElementById('ui-switcher-wrap');
        var trigger  = document.getElementById('ui-switcher-trigger');
        var drawer   = document.getElementById('ui-switcher-drawer');
        var overlay  = document.getElementById('ui-switcher-overlay');
        var closeBtn = document.getElementById('ui-switcher-close');

        if (!wrap) return;

        if (isPreview) {
            wrap.classList.add('uis-active');
        }

        // Mark initially selected option buttons
        config.forEach(function(group) {
            var saved = localStorage.getItem(PREFIX + group.id) || group.default;
            var btn = document.querySelector('.uis-option[data-group="' + group.id + '"][data-value="' + saved + '"]');
            if (btn) btn.classList.add('uis-selected');
        });

        function openDrawer() {
            drawer.classList.add('uis-open');
            overlay.classList.add('uis-open');
        }
        function closeDrawer() {
            drawer.classList.remove('uis-open');
            overlay.classList.remove('uis-open');
        }

        trigger.addEventListener('click', openDrawer);
        closeBtn.addEventListener('click', closeDrawer);
        overlay.addEventListener('click', closeDrawer);
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDrawer();
        });

        // Option toggle
        document.querySelectorAll('.uis-option').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var groupId = btn.dataset.group;
                var value   = btn.dataset.value;
                var group   = config.find(function(g) { return g.id === groupId; });
                if (!group) return;

                // Swap class on <html>
                group.options.forEach(function(opt) {
                    el.classList.remove(opt.value);
                });
                el.classList.add(value);

                // Persist
                localStorage.setItem(PREFIX + groupId, value);

                // Update selected state
                document.querySelectorAll('.uis-option[data-group="' + groupId + '"]').forEach(function(b) {
                    b.classList.remove('uis-selected');
                });
                btn.classList.add('uis-selected');
            });
        });
    })();
    </script>
    <?php
}
