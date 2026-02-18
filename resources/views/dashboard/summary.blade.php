<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Monitoring Dashboard | Axiom Scrumban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="mesh-gradient min-h-screen text-slate-800 antialiased">

    <div id="error-toast" class="error-toast font-bold">
        <span id="error-message">Connection Failed.</span>
    </div>

    <!-- Initial Loading Overlay -->
    <div id="app-loader">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-sm font-bold text-slate-500">Loading Task Monitoring Dashboard...</p>
        </div>
    </div>

    <div class="max-w-[1800px] mx-auto p-4 md:p-10">

        <!-- Header -->
        <header class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Task Monitoring Dashboard</h1>
                    <p class="text-slate-500 font-medium mt-2">Real-time overview of team tasks and workload distribution</p>
                </div>
                <button id="refreshBtn" class="bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Refresh Data</span>
                </button>
            </div>

            <!-- Status Indicator -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-200">
                <span id="status-indicator" class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span id="status-text" class="text-xs font-bold text-blue-600 uppercase tracking-widest">Checking Database...</span>
                <span class="text-xs text-blue-600 ml-2">PostgreSQL: axiom_scrumban</span>
            </div>
        </header>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="metric-card">
                <p class="metric-label">Total Tasks</p>
                <p class="metric-value" id="metricTotalTasks">--</p>
            </div>
            <div class="metric-card">
                <p class="metric-label">Team Utilization</p>
                <p class="metric-value" id="metricUtilization">--<span class="text-lg">%</span></p>
            </div>
            <div class="metric-card">
                <p class="metric-label">Overdue Tasks</p>
                <p class="metric-value text-red-600" id="metricOverdue">--</p>
            </div>
            <div class="metric-card">
                <p class="metric-label">In Progress</p>
                <p class="metric-value text-amber-600" id="metricInProgress">--</p>
            </div>
        </div>

        <!-- Bottleneck Alert -->
        <div id="bottleneckAlert" class="bottleneck-alert hidden">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="font-bold text-red-800">⚠ Team Member Overloaded</h3>
                    <p id="bottleneckMessage" class="text-sm text-red-700 mt-1"></p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-8 bg-white border border-slate-200 rounded-lg p-6">
            <h3 class="font-bold text-slate-900 mb-4">Filters & Sorting</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Filter by Status</label>
                    <div class="flex gap-2 flex-wrap">
                        <button class="filter-btn active" data-status="all">All</button>
                        <button class="filter-btn" data-status="todo">TODO</button>
                        <button class="filter-btn" data-status="progress">In Progress</button>
                        <button class="filter-btn" data-status="done">Done</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sort by Workload</label>
                    <div class="flex gap-2 flex-wrap">
                        <button class="filter-btn" data-sort="asc">Low to High</button>
                        <button class="filter-btn active" data-sort="desc">High to Low</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Show Bottlenecks</label>
                    <button id="showBottlenecksBtn" class="filter-btn">Highlight Overloaded Members</button>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-12 gap-8 mb-8">
            <!-- Charts Section -->
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <!-- Sprint Momentum -->
                <div class="bento-card p-8 rounded-2xl">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Sprint Momentum (Last 6 Sprints)</h3>
                    <div id="momentumChart" class="h-[300px]"></div>
                </div>

                <!-- Priority Distribution -->
                <div class="bento-card p-8 rounded-2xl">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Task Priority Distribution</h3>
                    <div id="priorityChart" class="h-[250px]"></div>
                </div>
            </div>

            <!-- Team Workload Section -->
            <div class="col-span-12 lg:col-span-4 bento-card p-8 rounded-2xl">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Team Workload Overview</h3>
                <div id="workloadList" class="space-y-6">
                    <p class="text-slate-400 text-sm italic">Loading team data...</p>
                </div>
            </div>
        </div>

        <!-- Overdue Tasks Section -->
        <div class="bento-card p-8 rounded-2xl">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Tasks at Risk (Overdue)</h3>
            <div id="overdueTasksList" class="divide-y divide-slate-200">
                <p class="text-slate-400 text-sm italic p-4">No overdue tasks found.</p>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>