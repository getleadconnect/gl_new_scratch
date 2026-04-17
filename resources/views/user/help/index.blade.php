@extends('layouts.user')

<style>
    .help-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        border-radius: 6px;
        cursor: pointer;
        transition: all .15s;
        text-align: left;
        width: 100%;
        background: transparent;
        border: none;
    }
    .help-tab:hover {
        background: #f3f4f6;
        color: #111827;
    }
    .help-tab.active {
        background: #f3f4f6;
        color: #111827;
        font-weight: 600;
    }
    .help-tab .tab-icon {
        flex-shrink: 0;
    }
    .help-panel {
        display: none;
    }
    .help-panel.active {
        display: block;
    }
    .help-panel h2 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }
    .help-panel h3 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin-top: 24px;
        margin-bottom: 10px;
    }
    .help-panel p {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 10px;
    }
    .help-panel ul {
        padding-left: 20px;
        list-style: disc;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.8;
    }
    .help-panel ul li strong {
        color: #111827;
    }
    .help-info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13px;
        color: #1e3a8a;
        margin: 12px 0;
    }
    .help-step {
        display: flex;
        gap: 12px;
        padding: 12px 14px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .help-step-num {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #18181b;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
    }
    .help-step-body {
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
    }
    .help-badge {
        display: inline-block;
        padding: 2px 8px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
    }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #f3f4f6; color: #374151; }

    .help-section-label {
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 10px 14px 4px;
    }
    .help-subtab {
        padding-left: 32px;
        font-size: 13px;
    }
</style>

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">Documentation and guides for each section of the user panel</p>
    </div>

    <!-- Two Column Layout -->
    <div style="display:flex;gap:16px;align-items:flex-start;">
        <!-- Left Tab Navigation -->
        <aside style="width:20%;flex-shrink:0;">
            <div class="bg-white rounded-lg shadow-sm p-3" style="border:1px solid #e4e4e4;">
                <nav class="space-y-1" id="helpTabs">
                    <button type="button" class="help-tab active" data-target="tab-campaigns">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        Campaigns
                    </button>
                    <button type="button" class="help-tab" data-target="tab-customers">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Customers
                    </button>
                    <button type="button" class="help-tab" data-target="tab-redeem">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        Redeem Prize
                    </button>
                    <button type="button" class="help-tab" data-target="tab-scratch-links">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Scratch Links
                    </button>
                    <button type="button" class="help-tab" data-target="tab-gifts-list">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        Gifts List
                    </button>
                    <button type="button" class="help-tab" data-target="tab-purchase-history">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Purchase History
                    </button>

                    <div class="help-section-label">Settings</div>

                    <button type="button" class="help-tab help-subtab" data-target="tab-settings-profile">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        My Profile
                    </button>
                    <button type="button" class="help-tab help-subtab" data-target="tab-settings-branches">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Branches
                    </button>
                    <button type="button" class="help-tab help-subtab" data-target="tab-settings-general">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        General Options
                    </button>
                    <button type="button" class="help-tab help-subtab" data-target="tab-settings-logo">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Logo & Favicon
                    </button>
                    <button type="button" class="help-tab help-subtab" data-target="tab-settings-purchase">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        Purchase Credits
                    </button>
                </nav>
            </div>
        </aside>

        <!-- Right Content Area -->
        <main style="flex:1;min-width:0;">
            <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8" style="border:1px solid #e4e4e4;">

                <!-- ═══ Campaigns Panel ═══ -->
                <div id="tab-campaigns" class="help-panel active">
                    <h2>Campaigns</h2>
                    <p>The <strong>Campaigns</strong> page is where you create and manage every scratch card campaign attached to your account. A campaign is the container that holds gifts, scratch links, and customer winners — so every promotion starts here.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Campaigns. (User/Child accounts only).
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add New Campaign</strong> button (top right) opens a modal to create a campaign.</li>
                        <li><strong>Filter bar</strong> — Status (Active / Inactive), End Date From, End Date To, plus Apply / Reset buttons.</li>
                        <li><strong>Campaigns table</strong> — server-side paginated list of your campaigns, newest first.</li>
                    </ul>

                    <h2 class="mt-4">Creating a campaign</h2>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Click <strong>Add New Campaign</strong>. A modal opens.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Enter the <strong>Campaign Name</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Pick an <strong>End Date</strong>. After this date, the campaign is treated as expired.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">Choose <strong>Status</strong> — Active to publish immediately, Inactive to keep as draft.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">5</div>
                        <div class="help-step-body">Optionally upload a <strong>Campaign Image</strong> (JPG / PNG / GIF, up to 50 MB). This image shows as the thumbnail in the list.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">6</div>
                        <div class="help-step-body">Click <strong>Save</strong>. The campaign appears at the top of the list.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>Tip:</strong> You can create the campaign first and add gifts later via the <strong>+ Gift</strong> button. A campaign without gifts won't generate prizes when customers scratch.
                    </div>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — show only Active or only Inactive campaigns.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>End Date From / To</strong> — narrow to campaigns ending within a specific date range.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table, or <strong>Reset</strong> to clear filters.
                        </div>
                    </div>

                    <h2 class="mt-4">Editing a campaign</h2>
                    <ul>
                        <li>Click the <strong>pencil</strong> icon to open the edit modal.</li>
                        <li>All fields (name, end date, status, image) can be updated.</li>
                        <li>Uploading a new image replaces and deletes the old one.</li>
                        <li>Leave the image field blank to keep the current one.</li>
                    </ul>

                    <h2 class="mt-4">Deleting a campaign</h2>
                    <ul>
                        <li>Click the <strong>trash</strong> icon on the row you want to remove.</li>
                        <li>Deletion is a <em>soft delete</em> — the campaign's <code>deleted_at</code> is set and it disappears from the list.</li>
                        <li>All gifts attached to that campaign are also deleted, and any uploaded images are cleaned up from storage.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Warning:</strong> Delete is permanent from the UI — the campaign and its gifts won't be visible anywhere in the app afterwards. Existing scratch links pointing to the campaign will stop working.
                    </div>

                    <h2 class="mt-4">Adding gifts to a campaign</h2>
                    <p>Click the <strong>+ Gift</strong> button on any row (or the <strong>Gift</strong> label). This takes you to the Gifts page for that campaign, where you can:</p>
                    <ul>
                        <li>Add prize entries with a description, image, and count.</li>
                        <li>Mark each gift as winning or losing.</li>
                        <li>Track how many of each prize are left as customers redeem them.</li>
                    </ul>

                    <h3>Next steps after creating a campaign</h3>
                    <ul>
                        <li><strong>Gifts List</strong> — add prizes to the campaign.</li>
                        <li><strong>Scratch Links</strong> — generate shareable links / QR codes tied to the campaign.</li>
                        <li><strong>Customers</strong> — view everyone who interacted with your campaign.</li>
                        <li><strong>Redeem</strong> — process prize redemption by customer unique ID.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Every campaign belongs only to your account.</li>
                        <li>Status and End Date decide whether new scratches are accepted.</li>
                        <li>Use the <strong>+ Gift</strong> shortcut to manage prizes without leaving this page.</li>
                        <li>Deletes are soft (recoverable from DB) but gifts and images are cleaned up.</li>
                    </ul>
                </div>

                <!-- ═══ Customers Panel ═══ -->
                <div id="tab-customers" class="help-panel">
                    <h2>Customers</h2>
                    <p>The <strong>Customers</strong> page is your master list of every person who has interacted with any of your scratch links. Each row represents one scratch attempt — including the customer's contact info, the campaign and gift involved, whether they won, and whether they've redeemed the prize.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Customers. All rows are scoped to your account only — you cannot see another user's customers.
                    </div>

                    <h3>Default behaviour</h3>
                    <p>When you open the page with no date filters applied, the list shows only the <strong>last 3 months</strong> of customer activity. This keeps the page fast for large datasets. To see older records, enter a Date From value (or both dates).</p>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — Campaign, Branch, Win Status, Redeem Status, Date From, Date To, plus Apply / Reset / Export CSV buttons.</li>
                        <li><strong>Customers table</strong> — server-side paginated list, newest first.</li>
                    </ul>

                     <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Campaign</strong> — narrow to customers from a single campaign only. Dropdown lists all your campaigns.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Branch</strong> — narrow to customers who chose a specific branch on the scratch form.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            <strong>Win Status</strong> — show only Winners or only Losers.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            <strong>Redeem Status</strong> — show only claimed or only unclaimed winners.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">5</div>
                        <div class="help-step-body">
                            <strong>Date From / Date To</strong> — override the default 3-month window with any range.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">6</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table, or <strong>Reset</strong> to clear filters (table falls back to the last 3 months).
                        </div>
                    </div>

                    <h3>Searching</h3>
                    <p>Use the DataTable search box to find a customer by:</p>
                    <ul>
                        <li><strong>Name</strong></li>
                        <li><strong>Mobile number</strong> (partial match)</li>
                        <li><strong>Unique Id</strong></li>
                        <li><strong>Campaign name</strong></li>
                        <li><strong>Branch name</strong></li>
                        <li><strong>Offer text</strong></li>
                        <li><strong>Bill Number</strong></li>
                    </ul>

                    <h2 class="mt-4">Exporting to CSV</h2>
                    <ul>
                        <li>Click <strong>Export CSV</strong>. A file named <code>customers_YYYYMMDD_HHMMSS.csv</code> downloads.</li>
                        <li>The export honors your current Campaign, Win Status, Redeem Status, and Date filters.</li>
                        <li>Use it for offline reports, marketing lists, or sharing winner data with your team.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> To get a winner list for a single campaign, set Campaign + Win Status = Win, then click Export CSV.
                    </div>

                    <h2 class="mt-4">Redeeming a prize</h2>
                    <p>The Customers page is <em>read-only</em> for redemption — you can see who has redeemed, but not mark anyone as redeemed here. To process a redemption:</p>
                    <ul>
                        <li>Go to the <strong>Redeem</strong> page from the sidebar.</li>
                        <li>Enter the customer's <strong>Unique Id</strong> (printed on their winning scratch screen or shared via WhatsApp).</li>
                        <li>Confirm the win and click redeem — the customer's row here will flip to <span class="help-badge" style="background:#dcfce7;color:#166534;">Yes</span>.</li>
                    </ul>

                    <h3>Where the data comes from</h3>
                    <ul>
                        <li>Every row comes from the <code>scratch_customers</code> table.</li>
                        <li>Rows are inserted automatically when a customer completes a scratch on a generated scratch link (with or without OTP, depending on your General Options).</li>
                        <li>The link's settings decide which fields are required (Bill No, Email, Branch) — missing fields show as <em>—</em>.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Defaults to last 3 months; expand with Date From / To.</li>
                        <li>Combine Campaign + Win Status for targeted winner lists.</li>
                        <li>Export CSV honors the on-screen filters.</li>
                        <li>Redemption happens on the <strong>Redeem</strong> page, not here.</li>
                        <li>The <strong>Unique Id</strong> is the key customers use to claim prizes.</li>
                    </ul>
                </div>

                <!-- ═══ Redeem Prize Panel ═══ -->
                <div id="tab-redeem" class="help-panel">
                    <h2>Redeem Prize</h2>
                    <p>The <strong>Redeem</strong> page is where you process prize claims. When a winning customer walks in with their scratch result, you look them up by <strong>Unique ID</strong>, verify the prize, and mark it as redeemed — all in one screen. This is the only place in the app where a scratch's redeem status can be flipped.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Redeem. User and Child accounts only. Each account can only search and redeem their own customers.
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Search card</strong> — a single input field and Search button to look up a customer by Unique ID.</li>
                        <li><strong>Customer details</strong> — appears after a successful search. Shows name, mobile, email, campaign, bill no, branch, and a "Redeemed" badge if already claimed.</li>
                        <li><strong>Offer text</strong> + <strong>gift image</strong> — the prize the customer won.</li>
                        <li><strong>Redeem Now</strong> button — visible only for winners who haven't redeemed yet.</li>
                    </ul>

                    <h3>How to process a redemption</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Ask the customer for their <strong>Unique ID</strong>. They would have received this on-screen after scratching, or via WhatsApp.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Type (or paste) the Unique ID into the search box and click <strong>Search</strong> (or press Enter).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Verify the customer details that appear — name, mobile, campaign, and offer.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">Click the <strong>Redeem Now</strong> button. A confirmation modal appears.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">5</div>
                        <div class="help-step-body">Confirm in the modal. The system marks the scratch as redeemed and records the timestamp + your account as the redeeming agent.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">6</div>
                        <div class="help-step-body">Hand over the prize to the customer. A green <strong>Redeemed</strong> badge now appears for this record.</div>
                    </div>

                    <h3>States you might see</h3>
                    <ul>
                        <li><strong>Winner, not yet redeemed</strong> — offer text + gift image shown, <em>Redeem Now</em> button active.</li>
                        <li><strong>Winner, already redeemed</strong> — green "Redeemed" badge, the date of redemption displayed, <em>Redeem Now</em> button hidden/disabled.</li>
                        <li><strong>Not a winner</strong> — offer field shows "Better luck next time" (or whatever loss text the campaign set) and no redeem button.</li>
                        <li><strong>No record found</strong> — an error message appears asking you to check the Unique ID.</li>
                    </ul>

                    <h3>Common errors</h3>
                    <ul>
                        <li><strong>"No record found for this Unique ID"</strong> — the ID doesn't exist, or it belongs to a different account. Double-check spelling.</li>
                        <li><strong>"This scratch has already been redeemed on dd-mm-yyyy HH:MM"</strong> — you or another agent already processed this one. No further action possible.</li>
                        <li><strong>"Please enter a Unique ID"</strong> — the search field was blank.</li>
                    </ul>

                    <h3>What the system records on redeem</h3>
                    <ul>
                        <li><strong>redeem</strong> flag flips from <code>0</code> &rarr; <code>1</code>.</li>
                        <li><strong>redeemed_on</strong> — the exact date/time the button was clicked.</li>
                        <li><strong>redeemed_agent</strong> — your user ID, so you can trace who processed each claim.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> The <strong>Customers</strong> page shows a Yes/No redeem badge for every winner. Use it to quickly see how many prizes are still pending. Filter by <em>Redeem Status = No</em> and <em>Win Status = Win</em> to get a list of unclaimed prizes.
                    </div>

                    <h3>Fraud protection</h3>
                    <ul>
                        <li>A scratch can be redeemed <strong>only once</strong> — subsequent attempts are blocked with the "already redeemed" error.</li>
                        <li>The system stores who (<em>redeemed_agent</em>) and when (<em>redeemed_on</em>) for every redemption, providing an audit trail.</li>
                        <li>The search is scoped to your own customers, so you cannot accidentally redeem someone else's winner.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Enter the Unique ID, click Search, verify details, then Redeem Now.</li>
                        <li>Each prize can be redeemed only once.</li>
                        <li>Non-winners can't be redeemed — no button appears for them.</li>
                        <li>The date/time and agent are logged automatically.</li>
                        <li>Check unclaimed winners via Customers &rarr; Win Status = Win, Redeem Status = No.</li>
                    </ul>
                </div>

                <!-- ═══ Scratch Links Panel ═══ -->
                <div id="tab-scratch-links" class="help-panel">
                    <h2>Scratch Links</h2>
                    <p>The <strong>Scratch Links</strong> page is where you turn a campaign into something customers can actually interact with. Each link is a short, shareable URL plus an auto-generated QR code that opens the scratch card page for a specific campaign. You can make single custom links (for marketing collateral) or bulk-generate thousands at once (for distribution as unique codes).</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Scratch Links. User and Child accounts only. Each account sees only its own links.
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add Link</strong> button — create a single link with a custom short code.</li>
                        <li><strong>Add Multiple Links</strong> button — bulk-generate many links at once with auto-random codes.</li>
                        <li><strong>Filter bar</strong> — Campaign and Link Type (Single / Multiple) filters.</li>
                        <li><strong>Download QR PDF</strong> button — export QR codes for a bulk batch into a printable PDF.</li>
                        <li><strong>Scratch Links table</strong> — server-side list of all your generated links.</li>
                    </ul>

                    <h3>Important table columns explained</h3>
                    <ul>
                        <li><strong>Offer Name</strong> — campaign name the link belongs to.</li>
                        <li><strong>Link</strong> — the full short URL you can share. Customers tap/scan to reach the scratch page.</li>
                        <li><strong>QrCode</strong> — the generated QR image thumbnail. Customers scan it from posters, flyers, or receipts.</li>
                        <li><strong>Code</strong> — the short code portion of the URL.</li>
                        <li><strong>Type</strong> — <em>Single</em> (created one-off) or <em>Multiple</em> (part of a bulk batch).</li>
                        <li><strong>Email (Required)</strong> — Yes/No flag: whether the customer must enter email to scratch.</li>
                        <li><strong>BillNo (Required)</strong> — Yes/No flag: whether a bill/receipt number is required.</li>
                        <li><strong>Branch (Required)</strong> — Yes/No flag: whether the customer must pick a branch.</li>
                    </ul>

                    <h2 class="mt-4">Creating a single link</h2>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Click <strong>Add Link</strong>. A modal opens.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Pick the <strong>Campaign</strong>. The campaign must have at least one active gift — otherwise link creation is blocked.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Enter a <strong>Short Code</strong> (minimum 5 characters, must be unique across the entire system).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">Toggle required fields — <strong>Bill Number</strong>, <strong>Branch</strong>, <strong>Email</strong>. These decide which inputs the customer must fill in before scratching.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">5</div>
                        <div class="help-step-body">Click <strong>Save</strong>. The full short URL and a QR code PNG are generated automatically.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>Why add gifts first?</strong> Scratch links refuse to create if the campaign has no active gifts, because there's nothing for a customer to win. Go to Gifts List and add at least one gift before generating links.
                    </div>

                    <h2 class="mt-4">Generating multiple links at once</h2>
                    <p>Use this when you need to print, say, 500 unique QR stickers or distribute unique codes on receipts.</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Click <strong>Add Multiple Links</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Pick the <strong>Campaign</strong>, the <strong>Link Count</strong> (1 to 1000 per batch), and toggle whether <strong>Branch</strong> is required.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Generate</strong>. The system creates that many links, each with a random 8-character uppercase code, and groups them into a named batch (timestamped) you can later select for PDF export.</div>
                    </div>

                    <h2 class="mt-4">Downloading QR codes as PDF</h2>
                    <ul>
                        <li>Click <strong>Download QR PDF</strong>.</li>
                        <li>Pick a link batch (each "Add Multiple Links" run becomes a batch).</li>
                        <li>The system builds a PDF with all the QR codes in that batch, ready for printing as stickers or on flyers.</li>
                    </ul>

                    <h3>Editing a link</h3>
                    <ul>
                        <li>Click the edit icon to change which <strong>Campaign</strong> the link points to, and toggle the <strong>Required</strong> flags.</li>
                        <li>You <em>cannot</em> change the short code or regenerate the QR — those are fixed once created.</li>
                    </ul>

                    <h3>Activating / deactivating a link</h3>
                    <ul>
                        <li>Use the toggle button in the Action column to flip <strong>Status</strong> between Active and Inactive.</li>
                        <li>Inactive links still exist but reject new scratches — useful when a campaign ends or if a code is being abused.</li>
                    </ul>

                    <h3>Deleting a link</h3>
                    <ul>
                        <li>Click the trash icon. Deletion is a <em>soft delete</em> — the link's <code>deleted_at</code> is set.</li>
                        <li>Existing customers who already scratched under this link keep their records intact.</li>
                    </ul>

                    <h3>Using the filters</h3>
                    <ul>
                        <li><strong>Campaign</strong> — show only links tied to a specific campaign.</li>
                        <li><strong>Link Type</strong> — show only Single or only Multiple links.</li>
                    </ul>

                    <h3>Required fields explained</h3>
                    <p>Each link can independently require or skip the following customer-side fields:</p>
                    <ul>
                        <li><strong>Bill Number</strong> — ideal for retail, linking scratches to a till receipt.</li>
                        <li><strong>Branch</strong> — forces the customer to pick a branch; useful if you're running the same campaign across multiple shops. Your branches come from Settings &rarr; Branches.</li>
                        <li><strong>Email</strong> — collects email for marketing follow-ups.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>A link needs a campaign with at least one active gift before it can be created.</li>
                        <li>Single link = custom short code. Multiple link = auto-random 8-char code.</li>
                        <li>Each link has its own required-fields settings, independent of the campaign.</li>
                        <li>Deactivate a link to stop new scratches without deleting it.</li>
                        <li>Use the QR PDF export for quick print-ready assets.</li>
                    </ul>
                </div>

                <!-- ═══ Gifts List Panel ═══ -->
                <div id="tab-gifts-list" class="help-panel">
                    <h2>Gifts List</h2>
                    <p>The <strong>Gifts List</strong> page is the consolidated view of every prize across all your campaigns. Each row is a single gift entry (e.g. "10% off coupon") with its own image, description, total count, remaining balance, and winning flag. Unlike the Gifts page inside a single campaign (used to create prizes), this page is for reviewing, editing, and managing prizes across everything you run.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Gifts List. User and Child accounts only. Rows are scoped to your own campaigns.
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter card</strong> — Campaign and Status filters with Filter button.</li>
                        <li><strong>+ Add Gift</strong> shortcut — pick a campaign and jump straight to its Gifts page to create a new prize.</li>
                        <li><strong>Gifts table</strong> — server-side paginated list of every gift across every campaign.</li>
                    </ul>

                    <h3>Important table columns explained</h3>
                    <ul>
                        <li><strong>Campaign</strong> — which campaign this gift belongs to.</li>
                        <li><strong>Image</strong> — gift thumbnail (or a "No Img" placeholder).</li>
                        <li><strong>Description</strong> — the offer text shown to the winning customer.</li>
                        <li><strong>Gift Count</strong> — total number of times this prize can be won in the campaign.</li>
                        <li><strong>Balance</strong> — remaining prizes that haven't been won yet. Drops by 1 every time a customer wins this gift.</li>
                        <li><strong>Win</strong> — golden star icon if this is a winning gift, or "—" for loss (non-winning) entries.</li>
                        <li><strong>Status</strong> — <span class="help-badge badge-active">Active</span> or <span class="help-badge badge-inactive">Inactive</span>. Inactive gifts are excluded from the random prize pool.</li>
                        <li><strong>Action</strong> — Edit, Delete, and Toggle Active/Inactive buttons.</li>
                    </ul>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Campaign</strong> — show gifts from a single campaign only.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — show only Active or only Inactive gifts.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Filter</strong> to reload the table.</div>
                    </div>

                    <h2 class="mt-4">Editing a gift</h2>
                    <p>Click the pencil icon on any row. A modal opens where you can change:</p>
                    <ul>
                        <li><strong>Description</strong> — the offer text customers see when they win.</li>
                        <li><strong>Image</strong> — upload a new gift image (JPG/PNG/GIF up to 50 MB). The old one is replaced in storage.</li>
                        <li><strong>Status</strong> — Active or Inactive.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Note:</strong> <code>gift_count</code> and <code>winning_status</code> are set when the gift is first created (from the Gifts page inside a campaign). This page only lets you tweak description, image, and status for safety — changing counts mid-campaign could over- or under-allocate prizes already in flight.
                    </div>

                    <h3>Activating / deactivating a gift</h3>
                    <ul>
                        <li>Use the toggle icon in the Action column to switch between Active and Inactive.</li>
                        <li>Inactive gifts are skipped when the system picks a random prize for a new scratch — useful to temporarily remove a prize without deleting it.</li>
                    </ul>

                    <h2 class="mt-4">Deleting a gift</h2>
                    <ul>
                        <li>Click the trash icon. Deletion is permanent.</li>
                        <li>If any customer has already won this gift, deletion is blocked with: <em>"Customers have already scratched this gift. It cannot be deleted."</em> Deactivate it instead.</li>
                        <li>When a fresh (never-won) gift is deleted, the system automatically <strong>restores its balance back to your scratch credits</strong> — the unused scratches aren't lost.</li>
                        <li>The gift image file is cleaned up from storage.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>How balance restore works:</strong> On create, a gift's <code>gift_count</code> is deducted from your Scratch Credits balance (moved to "used"). On delete of an unused gift, that deducted amount returns to your balance so you can reuse it elsewhere.
                    </div>

                    <h3>Gifts List vs. the Gifts page inside a campaign</h3>
                    <ul>
                        <li><strong>Campaign &rarr; + Gift</strong> — the only place to <em>create</em> new gifts, with full control over count and win/loss settings.</li>
                        <li><strong>Gifts List</strong> (this page) — a unified view to edit, deactivate, or delete gifts across all campaigns.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Every row is scoped to your account only.</li>
                        <li>Balance = remaining wins for this gift.</li>
                        <li>Inactive gifts are excluded from the random prize pool.</li>
                        <li>Cannot delete a gift if customers already won it — deactivate instead.</li>
                        <li>Deleting an unused gift restores its balance to your scratch credits.</li>
                        <li>Description, image, and status are editable here; count and win/loss are set at creation.</li>
                    </ul>
                </div>

                <!-- ═══ Purchase History Panel ═══ -->
                <div id="tab-purchase-history" class="help-panel">
                    <h2>Purchase History</h2>
                    <p>The <strong>Purchase History</strong> page is your personal credit top-up ledger. Every time you buy scratch credits — either through Razorpay checkout from the Dashboard or the Purchase Credits page, or when your SuperAdmin manually adds credits to your account — a row is recorded here. Use this as a self-service receipt log and for monthly reconciliation.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Purchase History (sidebar). User and Child accounts. You can only see your own purchases.
                    </div>

                    <h3>Default behaviour</h3>
                    <p>When you open the page with no dates applied, the table shows <strong>only the last 3 months</strong> of purchases. A blue note near the top reminds you of this default. To see older records, enter a Date From value.</p>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — Date From, Date To, and Apply / Reset / Export CSV buttons.</li>
                        <li><strong>Purchase history table</strong> — newest first, 50 rows per page.</li>
                    </ul>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Date From / Date To</strong> — override the default last-3-months window with any range you need.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            <strong>Reset</strong> clears both date fields and falls back to the default 3-month window.
                        </div>
                    </div>

                    <h2 class="mt-4">Exporting to CSV</h2>
                    <ul>
                        <li>Click <strong>Export CSV</strong>. A file named <code>my-purchase-history_YYYY-MM-DD_HHMMSS.csv</code> downloads.</li>
                        <li>The export honors your current Date From / Date To filters — whatever is on screen goes to the file.</li>
                        <li>Columns in the export: Sl No, Unique ID, User, Mobile, Role, Narration, Count, Amount, Date.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> Leave both date fields blank and click Export to download your last 3 months of purchases — the fastest option for monthly reconciliation.
                    </div>

                    <h3>Where do these rows come from?</h3>
                    <p>Every row on this page corresponds to a <code>purchase_scratch_history</code> record tied to your account. A row is inserted whenever your balance gets topped up:</p>
                    <ul>
                        <li><strong>Online purchase</strong> — Dashboard "Purchase Scratches" modal or Settings &rarr; Purchase Credits, paid via Razorpay.</li>
                        <li><strong>Manual top-up</strong> — SuperAdmin adds credits from the User Profile page (e.g. for offline payments or customer support).</li>
                    </ul>


                    <h3>Quick reference</h3>
                    <ul>
                        <li>Defaults to the last 3 months; override via Date From / Date To.</li>
                        <li>Shows both online Razorpay purchases and manual SuperAdmin top-ups.</li>
                        <li>Export CSV respects the on-screen filters.</li>
                        <li>Scoped to your account — you never see another user's data.</li>
                        <li>Use this page as a self-service receipt log.</li>
                    </ul>
                </div>

                <!-- ═══ Settings > My Profile Panel ═══ -->
                <div id="tab-settings-profile" class="help-panel">
                    <h2>Settings — My Profile</h2>
                    <p>The <strong>My Profile</strong> page under Settings is where you keep your account details up to date — your display name, contact info, and login password. These are the details that appear on invoices, are prefilled into payment forms, and identify you across the platform.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Settings &rarr; My Profile. User and Child accounts.
                    </div>

                    <h3>Editable fields</h3>
                    <ul>
                        <li><strong>Name</strong> — your display name.</li>
                        <li><strong>Email</strong> — must be unique across the system.</li>
                        <li><strong>Company Name</strong> — shown on receipts and in admin views.</li>
                        <li><strong>Country Code + Mobile</strong> — contact number.</li>
                        <li><strong>Address</strong> — optional free-text address.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Note:</strong> You <em>cannot</em> change the mobile number used for login directly here — that's managed by SuperAdmin from the Users List. The mobile field on this page is the contact number shown elsewhere.
                    </div>

                    <h3>Updating your profile</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Change any of the fields in the Profile card.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Click <strong>Save</strong>. Changes apply immediately.</div>
                    </div>

                    <h3>Changing your password</h3>
                    <p>The Change Password card lets you set a new login password:</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Enter a <strong>New Password</strong> (minimum 8 characters).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Confirm it in the second field (must match exactly).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Change Password</strong>. You'll stay logged in on this device, but other devices will need the new password next time.</div>
                    </div>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Email must be unique system-wide.</li>
                        <li>Password must be ≥ 8 characters and confirmed.</li>
                        <li>Login mobile is set by SuperAdmin, not editable here.</li>
                    </ul>
                </div>

                <!-- ═══ Settings > Branches Panel ═══ -->
                <div id="tab-settings-branches" class="help-panel">
                    <h2>Settings — Branches</h2>
                    <p>The <strong>Branches</strong> page lets you list all your physical locations (shops, outlets, stores, etc.). Once added, branches appear as choices on scratch forms when a scratch link has the "Branch Required" flag enabled — this lets you track which outlet drove each customer interaction.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Settings &rarr; Branches.
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add Branch</strong> button — opens a modal to create a new branch.</li>
                        <li><strong>Import</strong> button — bulk upload branches from an Excel file.</li>
                        <li><strong>Branches table</strong> with Name, Status, and Action columns.</li>
                    </ul>

                    <h3>Adding a branch manually</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Click <strong>Add Branch</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Enter the <strong>Branch Name</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Set <strong>Status</strong> to Active or Inactive.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">Click <strong>Save</strong>. The branch is now selectable on scratch forms.</div>
                    </div>

                    <h3>Bulk importing branches</h3>
                    <ul>
                        <li>Prepare an Excel file with columns: <code>branch_name</code>, <code>status</code> (<code>1</code> = Active, <code>0</code> = Inactive).</li>
                        <li>Click <strong>Import</strong>, upload the file, and the system reads each row and creates the branches.</li>
                        <li>Good for onboarding accounts with dozens of outlets at once.</li>
                    </ul>

                    <h3>Editing, deleting, activating</h3>
                    <ul>
                        <li><strong>Edit</strong> — change the name or status.</li>
                        <li><strong>Delete</strong> — permanently removes the branch.</li>
                        <li><strong>Toggle</strong> — flip Active ↔ Inactive without opening the edit modal.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> Inactive branches don't show up on scratch forms, so customers can't pick them. Use this to temporarily hide a closed outlet without losing its historical data.
                    </div>

                    <h3>Where branches are used</h3>
                    <ul>
                        <li><strong>Scratch Links</strong> — when creating a link with "Branch Required" = Yes, customers must pick a branch before scratching.</li>
                        <li><strong>Customers page</strong> — the Branch column shows which branch each customer selected.</li>
                        <li><strong>Customers filter</strong> — narrow to customers from a specific branch.</li>
                    </ul>
                </div>

                <!-- ═══ Settings > General Options Panel ═══ -->
                <div id="tab-settings-general" class="help-panel">
                    <h2>Settings — General Options</h2>
                    <p>The <strong>General Options</strong> page controls two system-wide toggles for your account: customer OTP verification on the scratch flow, and CRM integration for pushing customer details to your external CRM.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Settings &rarr; General Options.
                    </div>

                    <h3>OTP Verification</h3>
                    <p>A single toggle with two states: <span class="help-badge badge-active">Enabled</span> or <span class="help-badge badge-inactive">Disabled</span>.</p>
                    <ul>
                        <li><strong>Enabled</strong> — customers must verify their mobile with a WhatsApp OTP before scratching. Reduces fraud and duplicate entries.</li>
                        <li><strong>Disabled</strong> — customers can scratch without OTP verification. Faster, but less secure.</li>
                    </ul>
                    <p>Click the toggle to flip the state. The change applies instantly to all your scratch links.</p>

                    <h3>CRM API Token</h3>
                    <p>Used if you want customer details (name, email, mobile) to be pushed to your GetLead CRM automatically whenever a new customer scratches.</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Get your <strong>CRM API Token</strong> from your GetLead CRM account. It must start with <code>gl_</code>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Paste it into the <strong>CRM API Token</strong> field and click <strong>Save</strong>. Validation: tokens that don't start with <code>gl_</code> are rejected.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Use the <strong>Toggle CRM</strong> button to enable or disable pushing data without removing the token.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">To remove the integration entirely, click <strong>Remove Token</strong>. The token is deleted and pushes stop.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>What gets pushed?</strong> When a new customer scratches, the system sends their name, email, and mobile to <code>https://app.getlead.co.uk/api/gl-website-contacts</code> using your token.
                    </div>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>OTP toggle changes behavior of ALL your scratch links.</li>
                        <li>CRM tokens must start with <code>gl_</code>.</li>
                        <li>Disable CRM temporarily without losing the token.</li>
                        <li>Remove Token deletes the integration permanently.</li>
                    </ul>
                </div>

                <!-- ═══ Settings > Logo & Favicon Panel ═══ -->
                <div id="tab-settings-logo" class="help-panel">
                    <h2>Settings — Logo &amp; Favicon</h2>
                    <p>The <strong>Logo &amp; Favicon</strong> page lets you manage brand images for your account. These images can be used on scratch pages, receipts, or embedded in other branded touchpoints.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Settings &rarr; Logo &amp; Favicon.
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add</strong> button — upload a new logo or favicon.</li>
                        <li><strong>Images table</strong> — shows every uploaded asset with thumbnail, type, status, and actions.</li>
                    </ul>

                    <h3>Table columns explained</h3>
                    <ul>
                        <li><strong>Image</strong> — thumbnail preview.</li>
                        <li><strong>Name</strong> — a friendly label you give the asset.</li>
                        <li><strong>Type</strong> — <em>Logo</em> or <em>Favicon</em>, each shown with its own color badge.</li>
                        <li><strong>Status</strong> — <span class="help-badge badge-active">Active</span> or <span class="help-badge badge-inactive">Inactive</span>.</li>
                        <li><strong>Action</strong> — Edit, Delete.</li>
                    </ul>

                    <h3>Uploading a logo or favicon</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Click <strong>Add</strong>. A modal opens.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Give it a <strong>Name</strong> (e.g. "Shop Logo — Blue") so you can identify it later.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Choose <strong>Type</strong> — <em>Logo</em> (larger brand image) or <em>Favicon</em> (small site icon).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">Select the image file and set the status to Active.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">5</div>
                        <div class="help-step-body">Click <strong>Save</strong>. The image appears in the table.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>Tip:</strong> Upload different-sized logos for different use cases (e.g. one rectangular for headers, one square for social sharing). Mark only the one currently in use as Active.
                    </div>

                    <h3>Editing / deleting</h3>
                    <ul>
                        <li><strong>Edit</strong> — change name, type, replace image, or flip status.</li>
                        <li><strong>Delete</strong> — permanently removes the image and its file from storage.</li>
                    </ul>
                </div>

                <!-- ═══ Settings > Purchase Credits Panel ═══ -->
                <div id="tab-settings-purchase" class="help-panel">
                    <h2>Settings — Purchase Credits</h2>
                    <p>The <strong>Purchase Credits</strong> page is your self-service top-up screen. Use it whenever your scratch balance is running low. The flow is a 2-step wizard that selects a package and completes a Razorpay payment without ever leaving the app.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Settings &rarr; Purchase Credits. A Current Balance figure is always shown at the top of the page.
                    </div>

                    <h3>Step 1 — Select a package</h3>
                    <p>The packages table lists every bundle configured by your SuperAdmin — with scratch count, per-scratch rate, and total amount in ₹.</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Scan the table and click <strong>Select</strong> on the package you want.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">A summary card appears showing the count, rate, and total amount.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Proceed to Payment</strong>. The wizard advances to Step 2.</div>
                    </div>

                    <h3>Step 2 — Pay via Razorpay</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">The system spins up a Razorpay order. A "Pay Now" button appears with the final amount.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Click <strong>Pay Now</strong>. The Razorpay checkout modal opens — pay via UPI, cards, netbanking, wallets, etc.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">On success, a green confirmation appears and the page reloads. Your new balance reflects the purchase.</div>
                    </div>

                    <h3>If something goes wrong</h3>
                    <ul>
                        <li><strong>"Payment cancelled"</strong> — you closed the Razorpay modal. Click Change Plan to retry.</li>
                        <li><strong>"Payment verification failed"</strong> — a rare server-side signature mismatch. Contact support with your Razorpay Payment ID.</li>
                        <li><strong>"Network error"</strong> — connection dropped. Reload the page and try again.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Where does this record show up?</strong> Successful payments show up in both the Razorpay Payments table (superadmin only) and your own <strong>Purchase History</strong> page.
                    </div>

                    <h3>Need to see past purchases?</h3>
                    <p>Head to <strong>Purchase History</strong> from the sidebar. Every successful top-up (including ones your SuperAdmin added manually) appears there.</p>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Current balance is shown at the top of the page.</li>
                        <li>Only packages defined by SuperAdmin are available — no custom amounts.</li>
                        <li>Payment is via Razorpay (UPI / card / wallet / netbanking).</li>
                        <li>Credits appear instantly after successful payment.</li>
                        <li>Every top-up logs into Purchase History automatically.</li>
                    </ul>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
(function () {
    var tabs = document.querySelectorAll('#helpTabs .help-tab');
    var panels = document.querySelectorAll('.help-panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var target = tab.getAttribute('data-target');
            tabs.forEach(function (t) { t.classList.remove('active'); });
            panels.forEach(function (p) { p.classList.remove('active'); });
            tab.classList.add('active');
            var el = document.getElementById(target);
            if (el) el.classList.add('active');
        });
    });
})();
</script>

@endsection
