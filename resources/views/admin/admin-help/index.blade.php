@extends('layouts.admin')

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
    .badge-expired { background: #fee2e2; color: #991b1b; }
</style>

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-foreground">{{ $pageTitle }}</h1>
        <p class="mt-2 text-sm text-muted-foreground">Documentation and guides for each section of the admin panel</p>
    </div>

    <!-- Two Column Layout -->
    <div style="display:flex;gap:16px;align-items:flex-start;">
        <!-- Left Tab Navigation -->
        <aside style="width:20%;flex-shrink:0;">
            <div class="bg-white rounded-lg shadow-sm p-3" style="border:1px solid #e4e4e4;">
                <nav class="space-y-1" id="helpTabs">
                    <button type="button" class="help-tab active" data-target="tab-users">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Users List
                    </button>
                    <button type="button" class="help-tab" data-target="tab-campaigns">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        Campaigns
                    </button>
                    <button type="button" class="help-tab" data-target="tab-gifts-list">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                        Gifts List
                    </button>
                    <button type="button" class="help-tab" data-target="tab-customers">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Customers List
                    </button>
                    <button type="button" class="help-tab" data-target="tab-purchase-credits">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        Purchase Credits
                    </button>
                    <button type="button" class="help-tab" data-target="tab-purchase-history">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Purchase History
                    </button>
                </nav>
            </div>
        </aside>

        <!-- Right Content Area -->
        <main style="flex:1;min-width:0;">
            <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8" style="border:1px solid #e4e4e4;">

                <!-- ═══ Users List Panel ═══ -->
                <div id="tab-users" class="help-panel active">
                    <h2>Users List</h2>
                    <p>The <strong>Users</strong> page is where Admins (role_id 1) manage their own child users (role_id 3). These child users are sub-accounts you've added under your umbrella — typically store staff or shop operators who need to run campaigns on your behalf. From this page you can create, edit, delete, and review each child's subscription, credits, and contact details.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Users. Admin accounts (role_id = 1) only. You can only see and manage child users that belong to you (<code>parent_id = your id</code>).
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add New User</strong> button (top right) opens a modal to create a child user.</li>
                        <li><strong>Filter bar</strong> — Status, Created From, Created To.</li>
                        <li><strong>Users table</strong> with server-side pagination showing every child user under you.</li>
                    </ul>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — <em>Active</em> shows users with a valid subscription, <em>Expired</em> shows users past their end date, <em>Inactive</em> shows users with <code>status = 0</code>.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Created From / To</strong> — filter by registration date range.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table, or <strong>Reset</strong> to clear the filters.
                        </div>
                    </div>

                    <h2 class="mt-4">Adding a new child user</h2>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Click <strong>Add New User</strong>. A modal opens.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Enter <strong>Name</strong>, <strong>Email</strong>, <strong>Mobile Number</strong> (with country code), and optionally <strong>Company Name</strong> and <strong>Address</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Set a <strong>Password</strong> (minimum 6 characters).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">Optionally pick <strong>Subscription Start</strong> and <strong>Subscription End</strong> dates.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">5</div>
                        <div class="help-step-body">Click <strong>Save</strong>. The system auto-generates the Unique ID, assigns role_id = 3, sets <code>parent_id</code> to your account, and enables OTP settings by default.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>Important:</strong> The mobile number must be unique across the entire platform — it's how the child user logs in. If someone else is already using that number, the save will fail.
                    </div>

                    <h2 class="mt-4">Editing a child user</h2>
                    <ul>
                        <li>Click the <strong>pencil</strong> icon on the row to open the edit modal.</li>
                        <li>All fields can be updated. Leave the <strong>Password</strong> field blank to keep the current password.</li>
                        <li>Subscription dates are not edited here — those propagate from SuperAdmin and apply uniformly across all your child users.</li>
                    </ul>

                    <h2 class="mt-4">Deleting a child user</h2>
                    <ul>
                        <li>Click the <strong>trash</strong> icon to remove a user.</li>
                        <li>Deletion is a <em>soft delete</em> — the user's <code>deleted_at</code> is set and they disappear from the list.</li>
                        <li>Their historical data (campaigns, customers, purchases) remains intact in the database.</li>
                    </ul>

                    <h2 class="mt-4">Subscription and Credits</h2>
                    <p>Two columns let you review a child's usage at a glance:</p>
                    <ul>
                        <li><strong>Subscription</strong> — the validity window for the child's account. Set by SuperAdmin for the entire admin group.</li>
                        <li><strong>Credits</strong> — the child's current scratch balance. Green = credits available; zero or low credits mean they can't run new scratches.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>To top up a child's credits:</strong> go to <strong>Purchase Credits</strong> from the sidebar, pick the child user, select a package, and complete the Razorpay payment.
                    </div>

                    <h3>Related pages</h3>
                    <ul>
                        <li><strong>Campaigns</strong> — see all campaigns created by your child users.</li>
                        <li><strong>Gifts List</strong> — see gifts across all child campaigns.</li>
                        <li><strong>Customers List</strong> — see customers acquired through your child users' campaigns.</li>
                        <li><strong>Purchase Credits</strong> — top up scratch credits for a selected child user (Razorpay checkout).</li>
                        <li><strong>Purchase History</strong> — audit log of every credit top-up for your child users.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>This page only shows <em>your</em> child users — you can't see other admins' children.</li>
                        <li>Mobile number must be unique and is the login identifier.</li>
                        <li>Subscription dates for child users mirror the admin's subscription; managed by SuperAdmin.</li>
                        <li>Delete is soft — historical data is preserved.</li>
                        <li>Credits are topped up via the Purchase Credits page.</li>
                    </ul>
                </div>

                <!-- ═══ Campaigns Panel ═══ -->
                <div id="tab-campaigns" class="help-panel">
                    <h2>Campaigns</h2>
                    <p>The <strong>Campaigns</strong> page shows a read-only, consolidated list of every campaign created by your child users. As the Admin, you don't create campaigns yourself — your child users do — but this page lets you monitor them all in one place, filter by child user or status, and keep oversight of the promotional activity running under your account.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Sidebar &rarr; Campaigns. Admin accounts (role_id = 1) only. Shows campaigns where <code>user_id</code> belongs to one of your child users (role_id 3, <code>parent_id = your id</code>).
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — Child User (dropdown of your children), Status (Active / Inactive), End Date From, End Date To.</li>
                        <li><strong>Campaigns table</strong> with server-side pagination, newest first.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Read-only:</strong> No Add, Edit, or Delete actions here. This page is a monitoring dashboard — creation and management happen inside each child user's own panel.
                    </div>

                     <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Child User</strong> — narrow the list to campaigns from a specific child account only. The dropdown lists all child users under you.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — show only Active or only Inactive campaigns.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            <strong>End Date From / To</strong> — filter by the campaign's end-date window.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table or <strong>Reset</strong> to clear the filters.
                        </div>
                    </div>

                    <h3>Searching</h3>
                    <p>Use the DataTable search box to find campaigns by:</p>
                    <ul>
                        <li><strong>Campaign name</strong></li>
                        <li><strong>User name</strong> (the child user who owns the campaign)</li>
                    </ul>

                    <h3>How does a campaign get here?</h3>
                    <p>Campaigns appear automatically on this page as soon as a child user creates one from their User Panel (<em>Campaigns &rarr; Add New Campaign</em>). There is no approval step — everything your children create is visible to you instantly.</p>

                    <div class="help-info-box">
                        <strong>Where to make changes:</strong> If you need a campaign edited or deleted, ask the owning child user to do it from their own Campaigns page, or contact SuperAdmin for cross-account interventions.
                    </div>

                    <h3>Related pages</h3>
                    <ul>
                        <li><strong>Users</strong> — manage which child users exist and their subscription windows.</li>
                        <li><strong>Gifts List</strong> — review the prizes attached to each campaign.</li>
                        <li><strong>Customers List</strong> — see who has scratched under these campaigns.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Read-only monitoring view — no edits here.</li>
                        <li>Shows only campaigns created by <em>your</em> child users.</li>
                        <li>Status and End Date decide whether new scratches are accepted.</li>
                        <li>Combine Child User + Status filters for focused reviews.</li>
                        <li>For changes, the owning child user is the one who edits.</li>
                    </ul>
                </div>

                <!-- ═══ Gifts List Panel ═══ -->
                <div id="tab-gifts-list" class="help-panel">
                    <h2>Gifts List</h2>
                    <p>The <strong>Gifts List</strong> page is a read-only, consolidated view of every prize across every campaign run by your child users. Use it to audit prize setups, check winning/losing distribution, and see which gifts are currently live — all without digging into each child user's account one by one.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Gifts List. Admin accounts only. Rows are scoped to gifts owned by child users under your account.
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — Child User, Campaign, Status.</li>
                        <li><strong>Gifts table</strong> — server-side paginated list of every gift across all child campaigns, newest first.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Read-only:</strong> No Add, Edit, Delete, or Toggle actions here. To change a gift, ask the owning child user to edit from their own Gifts List page.
                    </div>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Child User</strong> — narrow to one child user's gifts only.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Campaign</strong> — narrow to a specific campaign's gifts (the dropdown lists every campaign across your children).
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — show only Active or only Inactive gifts.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table, or <strong>Reset</strong> to clear all filters.
                        </div>
                    </div>

                    <h3>Why this page matters</h3>
                    <p>As the Admin, you need a single place to review prize configurations without logging in as each child. This page answers questions like:</p>
                    <ul>
                        <li>How many prizes are still available across my children's campaigns?</li>
                        <li>Which gifts are marked as winning and which as losing?</li>
                        <li>Has a child user accidentally deactivated a popular prize?</li>
                        <li>Is a specific campaign set up correctly before it launches?</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Balance interpretation:</strong> <code>Balance = Gift Count - wins so far</code>. When it hits zero for a winning gift, no more customers can win that particular prize — subsequent scratches will be losers or will win other gifts.
                    </div>

                    <h3>Related pages</h3>
                    <ul>
                        <li><strong>Campaigns</strong> — each gift here belongs to one of those campaigns.</li>
                        <li><strong>Customers List</strong> — see which customers have actually won these gifts.</li>
                        <li><strong>Users</strong> — confirm which child user owns which campaign and gift.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Read-only monitoring view — no edits here.</li>
                        <li>Shows gifts across <em>all</em> your children's campaigns in one table.</li>
                        <li>Balance is the remaining uncalled count; drops by 1 on each win.</li>
                        <li>Inactive gifts are skipped in the random prize pool on the customer side.</li>
                        <li>To change a gift, the owning child user edits from their own Gifts List page.</li>
                    </ul>
                </div>

                <!-- ═══ Customers List Panel ═══ -->
                <div id="tab-customers" class="help-panel">
                    <h2>Customers List</h2>
                    <p>The <strong>Customers List</strong> page gives you a consolidated, read-only view of every customer scratch across all your child users' campaigns. Use it to audit winners, download reports, and verify which outlets and campaigns are driving the most engagement.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Customers List. Admin accounts only. Rows are scoped to customers that scratched under campaigns owned by your child users.
                    </div>

                    <h3>Default behaviour</h3>
                    <p>When you open the page with no date filters, the table shows only the <strong>last 3 months</strong> of customer activity. This keeps things fast for large datasets. Use Date From / To to go beyond that window.</p>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — Child User, Campaign, Win Status, Redeem Status, Date From, Date To, plus Apply / Reset / Export buttons.</li>
                        <li><strong>Customers table</strong> — server-side paginated list, newest first.</li>
                    </ul>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Child User</strong> — show customers from one child user's campaigns only.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Campaign</strong> — show customers from a single campaign.
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
                            <strong>Date From / Date To</strong> — override the default 3-month window.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">6</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table, or <strong>Reset</strong> to clear filters (falls back to last 3 months).
                        </div>
                    </div>

                    <h3>Searching</h3>
                    <p>Use the DataTable search box to find a customer by Name, Mobile, Unique Id, Campaign name, Branch name, Offer text, or Bill No.</p>

                    <h2 class="mt-4">Exporting to CSV</h2>
                    <ul>
                        <li>Click <strong>Export</strong> to download the filtered list as an Excel file named <code>customers_YYYYMMDD_HHMMSS.xlsx</code>.</li>
                        <li>The export honors all your current filters — Child User, Campaign, Win Status, Redeem Status, Date From, Date To.</li>
                        <li>Use this for reconciliation, marketing follow-ups, or sharing winner lists with your outlets.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> To get an unclaimed-winner list for a single outlet, filter by Child User + Win Status = Win + Redeem Status = No, then Export.
                    </div>

                    <h3>Read-only page</h3>
                    <ul>
                        <li>No add / edit / redeem actions here.</li>
                        <li>To mark a prize as redeemed, the owning child user uses their own Redeem page.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Defaults to last 3 months; expand with Date From / To.</li>
                        <li>Scoped to your child users' customers only.</li>
                        <li>Export respects the on-screen filters.</li>
                        <li>Combine Child User + Win Status for focused reports.</li>
                        <li>Redemption is handled by child users, not here.</li>
                    </ul>
                </div>

                <!-- ═══ Purchase Credits Panel ═══ -->
                <div id="tab-purchase-credits" class="help-panel">
                    <h2>Purchase Credits</h2>
                    <p>The <strong>Purchase Credits</strong> page lets you buy scratch credits <em>on behalf of</em> any of your child users. Use this whenever a child runs out of credits and can't buy them directly, or when you want to centrally manage billing. Payment flows through Razorpay and credits land on the selected child's balance immediately after verification.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Purchase Credits. Admin accounts only. You can only top up your own child users.
                    </div>

                    <h3>A 3-step wizard</h3>
                    <p>The page is a step-by-step wizard with a visual progress indicator: <em>Select User</em> &rarr; <em>Select Plan</em> &rarr; <em>Payment</em>.</p>

                    <h3>Step 1 — Select User</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            Scan the child users table. Each row shows <strong>Name</strong>, <strong>Mobile</strong>, and current <strong>Balance</strong> (green if credits available, red if zero).
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Click <strong>Select</strong> on the user you want to top up. Their name appears in the summary card.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Next - Select Package</strong>.</div>
                    </div>

                    <h3>Step 2 — Select Plan</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            The packages table lists every bundle configured by SuperAdmin — with scratch count, per-scratch rate, and total amount in ₹.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Click <strong>Select</strong> on a package. A summary card appears showing the count, rate, and total.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Proceed to Payment</strong> to advance, or <strong>Back</strong> to change the user.</div>
                    </div>

                    <h3>Step 3 — Payment</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">The system creates a Razorpay order. A Pay Now button appears with the amount and target user name.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Click <strong>Pay Now</strong>. Razorpay checkout opens — pay via UPI, cards, netbanking, wallets, etc.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">On success, a green confirmation appears. The page reloads and the child user's balance reflects the new credits.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>Who pays?</strong> You (the admin) complete the Razorpay payment, but the credits are added to the selected <em>child user's</em> balance — not yours. Use this when you centralise billing across your outlets.
                    </div>

                    <h3>If something goes wrong</h3>
                    <ul>
                        <li><strong>Payment cancelled</strong> — you closed the Razorpay modal. Click Change Plan to retry.</li>
                        <li><strong>Network error</strong> — connection dropped. Reload the page and start over.</li>
                        <li><strong>Verification failed</strong> — rare signature mismatch. Contact SuperAdmin with the Razorpay Payment ID.</li>
                    </ul>

                    <h3>Where the record shows up</h3>
                    <ul>
                        <li><strong>Payments</strong> (SuperAdmin) — the Razorpay transaction is logged.</li>
                        <li><strong>Purchase History</strong> (you and SuperAdmin) — a row appears showing the child user, count, amount, and date.</li>
                        <li><strong>The child user's Purchase History</strong> — they see it as a regular credit top-up.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>3-step wizard: User &rarr; Package &rarr; Payment.</li>
                        <li>You can only top up your own child users.</li>
                        <li>Credits appear on the <em>child's</em> balance, paid by <em>you</em>.</li>
                        <li>Packages come from SuperAdmin; no custom amounts.</li>
                        <li>Both Payments and Purchase History get updated automatically.</li>
                    </ul>
                </div>

                <!-- ═══ Purchase History Panel ═══ -->
                <div id="tab-purchase-history" class="help-panel">
                    <h2>Purchase History</h2>
                    <p>The <strong>Purchase History</strong> page is your consolidated credit-top-up ledger for all your child users. Every time a child receives scratch credits — whether you bought them via Razorpay from the Purchase Credits page, SuperAdmin added them manually, or the child bought them themselves — a row shows up here. Use it for monthly reconciliation and to audit who's been getting credits.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Purchase History. Admin accounts only. Rows are scoped to your child users only (role 3, <code>parent_id = your id</code>).
                    </div>

                    <h3>Default behaviour</h3>
                    <p>When you open the page with no date filters applied, the table shows <strong>only the last 3 months</strong> of top-ups. A blue note near the top reminds you. Override with Date From / Date To.</p>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — User (dropdown of your child users), Date From, Date To, plus Apply / Reset / Export CSV buttons.</li>
                        <li><strong>Purchase history table</strong> — server-side paginated, newest first.</li>
                    </ul>

                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>User</strong> — narrow to a single child user's top-ups only.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Date From / Date To</strong> — override the default 3-month window.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            <strong>Reset</strong> clears the filter fields (the table falls back to the default 3-month window).
                        </div>
                    </div>

                    <h2 class="mt-4">Exporting to CSV</h2>
                    <ul>
                        <li>Click <strong>Export CSV</strong>. A file named <code>purchase-history_YYYY-MM-DD_HHMMSS.csv</code> downloads.</li>
                        <li>The export honors all your current filters — User, Date From, Date To.</li>
                        <li>Columns in the export: Sl No, Unique ID, User, Mobile, Role, Narration, Count, Amount, Date.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> Leave all filters blank and click Export to download the last 3 months of all your child users' purchases — the most common reconciliation workflow.
                    </div>

                    <h3>Where does this data come from?</h3>
                    <p>Every row corresponds to a <code>purchase_scratch_history</code> record for one of your child users. Rows are inserted whenever their balance gets topped up:</p>
                    <ul>
                        <li><strong>Admin-initiated</strong> — you bought credits for them via Purchase Credits.</li>
                        <li><strong>Manual top-up</strong> — SuperAdmin added credits from the User Profile page.</li>
                        <li><strong>Child-initiated</strong> — the child user bought credits from their own Dashboard or Settings page.</li>
                    </ul>

                    <h3>Purchase History vs Payments</h3>
                    <ul>
                        <li><strong>Payments</strong> (SuperAdmin only) — raw Razorpay transactions, includes Failed / Pending attempts.</li>
                        <li><strong>Purchase History</strong> (this page) — only top-ups that actually landed on a balance.</li>
                        <li>Successful Razorpay payments show in both; manual top-ups only here.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Defaults to last 3 months; override with Date From / Date To.</li>
                        <li>Scoped to your child users' top-ups only.</li>
                        <li>Export CSV respects the on-screen filters.</li>
                        <li>Covers admin-initiated, SuperAdmin-manual, and child-initiated purchases.</li>
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
