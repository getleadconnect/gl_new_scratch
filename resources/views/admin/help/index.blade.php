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
        <!-- Left Tab Navigation (25%) -->
        <aside style="width:20%;flex-shrink:0;">
            <div class="bg-white rounded-lg shadow-sm p-3" style="border:1px solid #e4e4e4;">
                <nav class="space-y-1" id="helpTabs">
                    <button type="button" class="help-tab active" data-target="tab-users">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Users List
                    </button>
                    <button type="button" class="help-tab" data-target="tab-user-details">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        User Details
                    </button>
                    <button type="button" class="help-tab" data-target="tab-scratch-packages">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Scratch Packages
                    </button>
                    <button type="button" class="help-tab" data-target="tab-payments">
                        <svg class="w-4 h-4 tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payments
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

        <!-- Right Content Area (80%) -->
        <main style="flex:1;min-width:0;">
            <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8" style="border:1px solid #e4e4e4;">

                <!-- ═══ Users List Panel ═══ -->
                <div id="tab-users" class="help-panel active">
                    <h2>Users List</h2>
                    <p>The <strong>Users List</strong> page is where SuperAdmins manage every registered account across the platform — Admins, Users, and Child users. From here you can view, filter, create, edit, and delete user records, and drill into a single profile for subscription and credit details.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Users (SuperAdmin only).
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add New User</strong> button (top right) opens a modal to create a new user account.</li>
                        <li><strong>Filter bar</strong> with Role, Status, Created From, and Created To fields.</li>
                        <li><strong>Users table</strong> with server-side pagination showing every registered account (except the SuperAdmin himself).</li>
                    </ul>
                    
                    <h3>Using the filters</h3>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Role</strong> — narrow the list to Admins, Users, or Child accounts only.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — <em>Active</em> shows users with a valid subscription, <em>Expired</em> shows users past their end date, <em>Inactive</em> shows users with <code>status = 0</code>.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            <strong>Created From / To</strong> — filter by registration date range.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table with the chosen filters, or <strong>Reset</strong> to clear them.
                        </div>
                    </div>

                    <h2 class="mt-4">Adding a new user</h2>
                    <ul>
                        <li>Click <strong>Add New User</strong>. A modal appears.</li>
                        <li>Fill in name, email, country code + mobile, optional company and address.</li>
                        <li>Select a <strong>Role</strong>. For Child users (role 3) you must also pick a <strong>Parent Admin</strong>.</li>
                        <li>Set a password (minimum 6 characters).</li>
                        <li>Enter subscription start and end dates.</li>
                        <li>Click <strong>Save</strong>. The system auto-generates the Unique ID and enables OTP settings by default.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> The mobile number must be unique across the system — it's how the user logs in.
                    </div>

                    <h2 class="mt-4">Editing and deleting</h2>
                    <ul>
                        <li><strong>Edit</strong> (pencil icon) — opens the same modal pre-filled with the user's data. Password is optional; leave blank to keep the current one.</li>
                        <li><strong>Delete</strong> (trash icon) — soft deletes the user (sets <code>deleted_at</code>). The user disappears from the list but can be restored from the database if needed.</li>
                    </ul>

                    <h2 class="mt-4">Clicking a user name</h2>
                    <p>Opens the <strong>User Profile</strong> page, where you can:</p>
                    <ul>
                        <li>See their subscription period and remaining days.</li>
                        <li>View total / used / balance scratch credits.</li>
                        <li>Add a subscription period or scratch credits directly.</li>
                        <li>View the full scratch purchase history for that user.</li>
                        <li>For Admins, jump to their child users list.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Roles reference:</strong>
                        <ul style="margin-top:6px;">
                            <li><strong>0</strong> — SuperAdmin (you)</li>
                            <li><strong>1</strong> — Admin (manages Child users)</li>
                            <li><strong>2</strong> — User / Vendor (standalone business account)</li>
                            <li><strong>3</strong> — Child (sub-account under an Admin)</li>
                        </ul>
                    </div>
                </div>


                <!-- ═══ User Details Panel ═══ -->
                <div id="tab-user-details" class="help-panel">
                    <h2>User Details</h2>
                    <p>The <strong>User Informations</strong> page (also called User Profile) is the drill-down view you reach by clicking any name on the Users List. It shows a single user's profile, subscription status, scratch credit balance, and full purchase history — and lets you update their subscription period and top up scratch credits directly.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> In Users List Page &rarr; click any user name to show the user details page. Available to SuperAdmin only.
                    </div>

                    <h3>Page layout</h3>
                    <p>The page is split into two columns:</p>
                    <ul>
                        <li><strong>Left column</strong> — the user's profile card and subscription period card.</li>
                        <li><strong>Right column</strong> — action cards (update subscription, add scratch credits), the credits overview, and the purchase history table.</li>
                    </ul>
                    <p>The top-right of the page also shows the user's <strong>Total Scratch Balance</strong> as a large number for quick reference.</p>

                    <h3>Profile card (left)</h3>
                    <p>A snapshot of the user's account info:</p>
                    <ul>
                        <li><strong>Avatar</strong> — the first letter of the user's name.</li>
                        <li><strong>Name</strong> and role label (User, Admin, Child).</li>
                        <li><strong>Email</strong>, <strong>Mobile</strong> (country code + number), <strong>Company</strong>, and <strong>Address</strong>.</li>
                        <li><strong>Status</strong> — <span class="help-badge badge-active">Active</span>, <span class="help-badge badge-expired">Expired</span>, or <span class="help-badge badge-inactive">Inactive</span>. If the user has no subscription yet, you'll see "Subscription not found" in red.</li>
                        <li><strong>No of child users</strong> (Admins only) — count of child users under this admin, plus a "View Child Users" button that navigates to their child users list.</li>
                    </ul>

                    <h3>Subscription Period card (left)</h3>
                    <ul>
                        <li><strong>Start Date</strong> and <strong>End Date</strong> — the active subscription window.</li>
                        <li><strong>Days Left</strong> — remaining days. Shows green when safe, orange if ≤ 7 days, red when expired.</li>
                    </ul>

                    <h2 class="mt-4">Changing the subscription period</h2>
                    <p>Use the <strong>Add Subscription Period</strong> card on the right:</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Pick a new <strong>Start Date</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Pick an <strong>End Date</strong> (must be on or after the start date).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Update Subscription</strong>. The page reloads with the new dates.</div>
                    </div>
                    <div class="help-info-box">
                        <strong>Note:</strong> Updating the subscription from an Admin (role 1) profile propagates the same dates to all their Child users automatically. Updating a Child also syncs the parent Admin's period.
                    </div>

                    <h2 class="mt-4">Changing the profile details</h2>
                    <p>Profile info (name, email, mobile, company, address, password) is edited from the <strong>Users List</strong> page, not this page.</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Go back to <strong>Admin &rarr; Users</strong>.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Find the user's row and click the <strong>Edit</strong> pencil icon under Actions.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">The edit modal opens pre-filled. Update fields, leave <strong>Password</strong> blank to keep the current one, and click <strong>Save</strong>.</div>
                    </div>

                    <h2 class="mt-4">Adding scratch credits</h2>
                    <p>Use the <strong>Add Scratch Credits</strong> card on the right:</p>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Open the <strong>Scratch Credits</strong> dropdown. It lists every package you've configured (count + total amount in ₹).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Select the package size you want to add.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Add Scratch</strong>. The credits are added to the user's balance, a new row is recorded in Scratch Purchase History, and the page refreshes.</div>
                    </div>
                    <div class="help-info-box">
                        <strong>Behind the scenes:</strong> The system looks up the package rate, updates <code>scratch_counts</code> (total + balance) for the user, and inserts a new <code>purchase_scratch_history</code> record. Previous active history rows are marked as superseded.
                    </div>

                    <h2 class="mt-4">Scratch Credits Overview</h2>
                    <p>A card showing three side-by-side stats:</p>
                    <ul>
                        <li><strong>Total Scratch</strong> — lifetime total credits purchased for this user (blue).</li>
                        <li><strong>Used Scratch</strong> — credits consumed through campaigns (red).</li>
                        <li><strong>Balance Scratch</strong> — remaining usable credits (green).</li>
                    </ul>
                    <p>This card is hidden for Admin (role 1) accounts since they don't use credits directly — their child users do.</p>

                    <h2 class="mt-4">Scratch Purchase History</h2>
                    <p>A DataTable at the bottom showing every credit top-up for this user: date, narration (auto-generated note), and count. Use this to audit when and how credits were added.</p>

                    <h3>Quick reference — what you can do here</h3>
                    <ul>
                        <li>View full profile and subscription info at a glance.</li>
                        <li>Update the subscription start/end dates.</li>
                        <li>Top up scratch credits by selecting a package.</li>
                        <li>Audit the complete purchase history.</li>
                        <li>(For Admins) jump to their child users list.</li>
                    </ul>
                </div>

                <!-- ═══ Scratch Packages Panel ═══ -->
                <div id="tab-scratch-packages" class="help-panel">
                    <h2>Scratch Packages</h2>
                    <p>The <strong>Scratch Rate</strong> page (also called Scratch Packages) is where SuperAdmins define the pricing tiers that Users and Admins see when purchasing scratch credits. Each package is a bundle — e.g. "5,000 scratches at ₹0.50 per scratch = ₹2,500". Only packages you create here will appear in the purchase dropdowns across the app.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Scratch Rate (SuperAdmin only).
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Add New Package</strong> card (top) with <em>Scratch Count</em> and <em>Rate</em> fields.</li>
                        <li><strong>Packages table</strong> listing every existing package ordered by scratch count (ascending).</li>
                    </ul>

                    <h2 class="mt-4">Adding a new package</h2>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">Enter a <strong>Scratch Count</strong> (positive integer). Must be unique — you can't have two packages with the same count.</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">Enter the <strong>Rate</strong> per scratch (decimal, e.g. <code>0.50</code>).</div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">Click <strong>Save</strong>. The total amount is computed automatically and the new row appears in the table.</div>
                    </div>

                    <div class="help-info-box">
                        <strong>Formula:</strong> <code>Total Amount = Scratch Count × Rate</code>. You don't enter the total — the system calculates and stores it when you save.
                    </div>

                    <h2 class="mt-4">Editing a package</h2>
                    <ul>
                        <li>Click the <strong>pencil</strong> icon on the row you want to change.</li>
                        <li>The Add form switches to <strong>Edit</strong> mode and pre-fills the values.</li>
                        <li>Update <strong>Scratch Count</strong> or <strong>Rate</strong> and click <strong>Update</strong>.</li>
                        <li>The total amount is recalculated automatically.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Warning:</strong> Changing a package's <code>scratch_count</code> will not affect past purchases — historical <code>payment_history</code> and <code>purchase_scratch_history</code> rows keep their original values. But the new count will be what future buyers see.
                    </div>

                    <h2 class="mt-4">Deleting a package</h2>
                    <ul>
                        <li>Click the <strong>trash</strong> icon to remove a package.</li>
                        <li>Deletion is permanent for this table (no soft delete).</li>
                        <li>Old purchase records referencing the deleted package are not touched — their data stays intact.</li>
                    </ul>

                    <h2 class="mt-4">Where these packages appear</h2>
                    <p>Once created, a package is immediately available in every purchase flow in the app:</p>
                    <ul>
                        <li><strong>User Panel</strong> — Dashboard "Purchase Scratches" modal and Settings &rarr; Purchase Credits page.</li>
                        <li><strong>Admin Panel</strong> — Purchase Credits page (buying for a child user).</li>
                        <li><strong>SuperAdmin &rarr; Users &rarr; User Profile</strong> — the Add Scratch Credits dropdown.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Packages drive every credit purchase in the system.</li>
                        <li>Scratch Count must be unique; Rate is a decimal.</li>
                        <li>Total Amount is always <code>count × rate</code>, auto-computed.</li>
                        <li>Sort order in lists is ascending by scratch count.</li>
                        <li>Edits apply only to future purchases; past records stay as-is.</li>
                    </ul>
                </div>

                <!-- ═══ Payments Panel ═══ -->
                <div id="tab-payments" class="help-panel">
                    <h2>Payments</h2>
                    <p>The <strong>Payments</strong> page is a read-only audit log of every online payment captured through Razorpay — both successful purchases and any failed or pending attempts. Use it to reconcile revenue, investigate specific transactions, and export financial data to CSV.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Payments (SuperAdmin only, role_id = 0). Title displays "<em>(Online Only)</em>" — manual credit top-ups added from the User Profile page are NOT shown here (those live in Purchase History).
                    </div>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> with Status, Date From, Date To fields, plus Apply / Reset / Export CSV buttons.</li>
                        <li><strong>Total Amount</strong> tile — sums the amount of all rows matching the current filter. Defaults to all successful payments when no filter is set.</li>
                        <li><strong>Payments table</strong> with server-side pagination, newest first.</li>
                    </ul>

                    <h2 class="mt-4">Using the filters</h2>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>Status</strong> — narrow to Success, Failed, or Pending only.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Date From / Date To</strong> — filter by payment date range.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table and refresh the Total Amount tile. The Total Amount always reflects the current filter — great for quickly summing revenue for a given month or status.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            <strong>Reset</strong> clears all filters.
                        </div>
                    </div>

                    <h2 class="mt-4">Total Amount tile</h2>
                    <p>The large ₹ figure on the right of the filter bar is a live sum of the <code>amount</code> column for the filtered rows.</p>
                    <ul>
                        <li>No filter applied &rarr; sums all <strong>Success</strong> payments (ignores Failed/Pending).</li>
                        <li>Status filter applied &rarr; sums payments matching that status only.</li>
                        <li>Date filters narrow the sum to that period.</li>
                    </ul>

                    <h2 class="mt-4">Exporting to CSV</h2>
                    <ul>
                        <li>Click <strong>Export CSV</strong>. A file named <code>payments_YYYY-MM-DD_HHMMSS.csv</code> downloads.</li>
                        <li>The export respects the current Status / Date From / Date To filters — whatever is on screen goes to the file.</li>
                        <li>Columns in the export: Sl No, User Name, Email, Mobile, Unique ID, Order ID, Payment ID, Scratch Count, Amount, Currency, Status, Date.</li>
                    </ul>

                    <h3>Searching</h3>
                    <p>Use the DataTable's search box to find:</p>
                    <ul>
                        <li>A buyer by <strong>name</strong> or <strong>email</strong>.</li>
                        <li>A specific transaction by its <strong>Payment Id</strong> (paste the Razorpay ID).</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Data source:</strong> Every row comes from the <code>payment_history</code> table, joined with <code>users</code>. Records are inserted automatically when Razorpay returns a verified signature from <code>verifyPayment()</code>.
                    </div>

                    <h2 class="mt-4">Payments vs Purchase History</h2>
                    <ul>
                        <li><strong>Payments</strong> — only Razorpay online transactions (one row per payment attempt).</li>
                        <li><strong>Purchase History</strong> — every credit top-up, including manual ones added by SuperAdmin from the User Profile page.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Read-only audit log — no edit/delete actions here.</li>
                        <li>Filters and the Total Amount tile stay in sync.</li>
                        <li>Export CSV matches the on-screen filter.</li>
                        <li>Search by user name, email, or Razorpay Payment ID.</li>
                    </ul>
                </div>

                <!-- ═══ Purchase History Panel ═══ -->
                <div id="tab-purchase-history" class="help-panel">
                    <h2>Purchase History</h2>
                    <p>The <strong>Purchase History</strong> page is a consolidated log of every scratch credit top-up that ever happened in the system — including both online Razorpay purchases and manual credits added by SuperAdmin from the User Profile page. Use it as your master credit ledger when reconciling balances, auditing SuperAdmin activity, or exporting data for reporting.</p>

                    <div class="help-info-box">
                        <strong>Access:</strong> Purchase History (SuperAdmin only, role_id = 0).
                    </div>

                    <h3>Default behaviour</h3>
                    <p>When you open the page with no filters applied, the list shows purchases from the <strong>last 3 months</strong> for all Users (role_id = 2) and Child users (role_id = 3). A blue note near the top of the table reminds you of this default.</p>

                    <h3>What you see on the page</h3>
                    <ul>
                        <li><strong>Filter bar</strong> — User dropdown, Date From, Date To, and Apply / Reset / Export CSV buttons.</li>
                        <li><strong>Purchase history table</strong> — server-side, newest first, paginated 50 rows per page.</li>
                    </ul>

                    <h2 class="mt-4">Using the filters</h2>
                    <div class="help-step">
                        <div class="help-step-num">1</div>
                        <div class="help-step-body">
                            <strong>User</strong> dropdown — narrow the list to a single user. Contains all Users and Child users, sorted alphabetically.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">2</div>
                        <div class="help-step-body">
                            <strong>Date From / Date To</strong> — override the default last-3-months window with any range you need.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">3</div>
                        <div class="help-step-body">
                            Click <strong>Apply</strong> to reload the table with the chosen filters.
                        </div>
                    </div>
                    <div class="help-step">
                        <div class="help-step-num">4</div>
                        <div class="help-step-body">
                            <strong>Reset</strong> clears all filter fields. The table then falls back to its default 3-month window.
                        </div>
                    </div>

                    <h2 class="mt-4">Exporting to CSV</h2>
                    <ul>
                        <li>Click the <strong>Export CSV</strong> button. A file named <code>purchase-history_YYYY-MM-DD_HHMMSS.csv</code> downloads.</li>
                        <li>The export uses the exact same filters as the on-screen table (User, Date From, Date To).</li>
                        <li>Exported columns: Sl No, Unique ID, User, Mobile, Role, Narration, Count, Amount, Date.</li>
                    </ul>

                    <div class="help-info-box">
                        <strong>Tip:</strong> Leave all filter fields blank and click Export to download the last 3 months — the most common use case for monthly reconciliation.
                    </div>

                    <h3>Where the data comes from</h3>
                    <ul>
                        <li>Every row reads from <code>purchase_scratch_history</code> joined with <code>users</code>.</li>
                        <li>A row is inserted every time the user's credit balance is topped up — whether through Razorpay checkout or the SuperAdmin's "Add Scratch Credits" card.</li>
                        <li>The <strong>status</strong> column in the DB is not displayed here; it's an internal flag marking the latest active purchase per user.</li>
                    </ul>

                    <h2 class="mt-4">Purchase History vs Payments</h2>
                    <ul>
                        <li><strong>Payments</strong> — Razorpay online transactions only. Includes Failed / Pending attempts.</li>
                        <li><strong>Purchase History</strong> — every credit top-up that actually added to a balance, including manual ones added by SuperAdmin.</li>
                        <li>For any successful Razorpay payment you'll see matching rows in <em>both</em> pages. Manual top-ups appear only here.</li>
                    </ul>

                    <h3>Quick reference</h3>
                    <ul>
                        <li>Defaults to the last 3 months; override with Date From / Date To.</li>
                        <li>Filter by a single user via the dropdown.</li>
                        <li>Export CSV respects the on-screen filters.</li>
                        <li>Covers both online purchases and manual SuperAdmin top-ups.</li>
                        <li>Click the user name to jump to their full profile.</li>
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
