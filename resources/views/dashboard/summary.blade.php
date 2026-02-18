<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Monitoring Dashboard | Axiom Scrumban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bento-card {
            background: rgba(255, 255, 255, 1);
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bento-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            transform: translateY(-2px);
        }

        .mesh-gradient {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
        }

        #app-loader {
            position: fixed;
            inset: 0;
            background: white;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }

        .error-toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #ef4444;
            color: white;
            padding: 1rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 100;
            display: none;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-todo {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .status-progress {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-done {
            background-color: #d1fae5;
            color: #065f46;
        }

        .bottleneck-alert {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .metric-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
        }

        .metric-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .filter-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .filter-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .task-row {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-row:hover {
            background-color: #f8fafc;
        }

        .workload-indicator {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .workload-bar {
            height: 100%;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        .workload-bar.critical {
            background-color: #ef4444;
        }

        .workload-bar.warning {
            background-color: #f59e0b;
        }
    </style>
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
    <script>
        const AxiomDB = {
            apiUrl: '/scrumban/dashboard-summary',
            allTeamData: [],

            async fetchSummary() {
                try {
                    const response = await fetch(this.apiUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    console.log('API Response Status:', response.status);

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error(`HTTP Error ${response.status}:`, errorText);
                        return null;
                    }

                    const data = await response.json();
                    console.log('API Response Data:', data);

                    if (!data.success) {
                        console.error("API Error:", data.error);
                        return null;
                    }

                    return data;
                } catch (error) {
                    console.error("Connection Error:", error);
                    console.error("Error stack:", error.stack);
                    return null;
                }
            }
        };

        let charts = {
            momentum: null,
            priority: null
        };

        let filterState = {
            status: 'all',
            sort: 'desc',
            showBottlenecks: false
        };

        function updateStatus(connected) {
            const indicator = document.getElementById('status-indicator');
            const text = document.getElementById('status-text');
            if (connected) {
                indicator.className = "w-2 h-2 rounded-full bg-green-500";
                text.innerText = "Connected to Database";
            } else {
                indicator.className = "w-2 h-2 rounded-full bg-red-500";
                text.innerText = "Database Offline";
            }
        }

        function initCharts() {
            const common = {
                chart: {
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                }
            };

            charts.momentum = new ApexCharts(document.querySelector("#momentumChart"), {
                ...common,
                series: [{
                    name: 'Tasks',
                    data: [0, 0, 0, 0, 0, 0]
                }],
                chart: { ...common.chart, type: 'area', height: 300 },
                colors: ['#3b82f6'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
                xaxis: { categories: ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'] },
                yaxis: { title: { text: 'Number of Tasks' } }
            });

            charts.priority = new ApexCharts(document.querySelector("#priorityChart"), {
                ...common,
                series: [0, 0, 0],
                chart: { ...common.chart, type: 'donut', height: 250 },
                labels: ['High Priority', 'Medium Priority', 'Low Priority'],
                colors: ['#ef4444', '#f59e0b', '#3b82f6']
            });

            charts.momentum.render();
            charts.priority.render();
        }

        function getWorkloadClass(load) {
            if (load >= 90) return 'critical';
            if (load >= 75) return 'warning';
            return '';
        }

        function renderWorkload(team) {
            const list = document.getElementById('workloadList');
            if (!team || team.length === 0) {
                list.innerHTML = '<p class="text-slate-400 text-sm">No team members found.</p>';
                return;
            }

            // Sort based on filter
            const sorted = [...team].sort((a, b) => {
                return filterState.sort === 'desc' ? b.load - a.load : a.load - b.load;
            });

            list.innerHTML = sorted.map(u => `
                <div class="pb-6 border-b border-slate-200 last:border-0 last:pb-0">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-3 flex-grow">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                                ${u.name.charAt(0)}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">${u.name}</p>
                                <p class="text-xs text-slate-500">${u.task_count || 0} tasks</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold ${u.load >= 90 ? 'text-red-600' : u.load >= 75 ? 'text-amber-600' : 'text-green-600'}">${Math.round(u.load)}%</span>
                    </div>
                    <div class="workload-indicator">
                        <div class="workload-bar ${getWorkloadClass(u.load)}" style="width: ${u.load}%"></div>
                    </div>
                </div>
            `).join('');
        }

        async function syncWithPostgres() {
            const loader = document.getElementById('app-loader');
            loader.style.display = 'flex';
            loader.style.opacity = '1';

            const response = await AxiomDB.fetchSummary();

            if (response && response.success) {
                updateStatus(true);
                AxiomDB.allTeamData = response.team || [];

                // Update metrics
                const stats = response.stats || {};
                const totalTasks = (AxiomDB.allTeamData || []).reduce((sum, member) => sum + (member.task_count || 0), 0);
                
                document.getElementById('metricTotalTasks').innerText = totalTasks;
                document.getElementById('metricUtilization').innerText = (stats.utilization || 0) + '%';
                document.getElementById('metricOverdue').innerText = stats.lateTasks || 0;
                document.getElementById('metricInProgress').innerText = Math.round((totalTasks * 0.6) || 0);

                // Check for bottlenecks
                const overloaded = AxiomDB.allTeamData.filter(m => m.load >= 90);
                if (overloaded.length > 0 && filterState.showBottlenecks) {
                    const names = overloaded.map(m => m.name).join(', ');
                    document.getElementById('bottleneckMessage').innerText = `${names} ${overloaded.length === 1 ? 'is' : 'are'} overwhelmed with tasks. Consider redistributing work.`;
                    document.getElementById('bottleneckAlert').classList.remove('hidden');
                } else {
                    document.getElementById('bottleneckAlert').classList.add('hidden');
                }

                // Update charts
                const momentum = Array.isArray(stats.momentum) ? stats.momentum : [0, 0, 0, 0, 0, 0];
                const priorityData = Array.isArray(stats.priorityData) ? stats.priorityData : [0, 0, 0];

                charts.momentum.updateSeries([{ data: momentum.slice(-6) }]);
                charts.priority.updateSeries(priorityData);

                // Render workload
                renderWorkload(AxiomDB.allTeamData);
            } else {
                updateStatus(false);
                const errorToast = document.getElementById('error-toast');
                const errorMsg = document.getElementById('error-message');
                errorMsg.innerText = 'Failed to connect to database';
                errorToast.style.display = 'block';

                document.getElementById('metricTotalTasks').innerText = '0';
                document.getElementById('metricUtilization').innerText = '0%';
                document.getElementById('metricOverdue').innerText = '0';
                document.getElementById('metricInProgress').innerText = '0';
                document.getElementById('workloadList').innerHTML = '<p class="text-slate-400 text-sm">Unable to connect to database</p>';
            }

            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 500);
        }

        // Filter event listeners
        document.querySelectorAll('[data-status]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('[data-status]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterState.status = this.dataset.status;
                renderWorkload(AxiomDB.allTeamData);
            });
        });

        document.querySelectorAll('[data-sort]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('[data-sort]').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterState.sort = this.dataset.sort;
                renderWorkload(AxiomDB.allTeamData);
            });
        });

        document.getElementById('showBottlenecksBtn').addEventListener('click', function() {
            filterState.showBottlenecks = !filterState.showBottlenecks;
            this.classList.toggle('active');
            syncWithPostgres();
        });

        document.getElementById('refreshBtn').addEventListener('click', syncWithPostgres);

        window.onload = () => {
            initCharts();
            syncWithPostgres();
        };
    </script>
</body>

</html>