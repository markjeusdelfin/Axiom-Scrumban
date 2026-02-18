<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Oversight | TaskFlow Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
        .scroll-hide::-webkit-scrollbar { width: 4px; }
        .scroll-hide::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        .progress-ring {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        .filter-loading {
            opacity: 0.5;
            pointer-events: none;
            filter: blur(1px);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="mesh-gradient min-h-screen text-slate-800 antialiased">

    <div class="max-w-[1600px] mx-auto p-4 md:p-10">

        <!-- Header: Modern Minimalism + New Filtering UI -->
        <header class="flex flex-col xl:flex-row justify-between items-start xl:items-end mb-12 gap-8">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 mb-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Live Operations</span>
                </div>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Executive Summary</h1>
                <p class="text-slate-500 font-medium text-lg">Real-time workload distribution and throughput analysis.</p>
            </div>

            <!-- Enhanced Filtering & Sorting Controls -->
            <div class="flex flex-wrap items-center gap-4 bg-white/70 backdrop-blur-xl p-3 rounded-[2rem] border border-white shadow-sm w-full xl:w-auto">
                <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <select id="employeeFilter" class="bg-transparent text-xs font-bold text-slate-600 outline-none cursor-pointer pr-4">
                        <option value="all">All Employees</option>
                        <option value="sarah">Sarah Chen</option>
                        <option value="marcus">Marcus T.</option>
                        <option value="elena">Elena Rodriguez</option>
                    </select>
                </div>
                
                <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <select id="statusFilter" class="bg-transparent text-xs font-bold text-slate-600 outline-none cursor-pointer pr-4">
                        <option value="all">Status: All</option>
                        <option value="overloaded">Overloaded</option>
                        <option value="healthy">Healthy</option>
                        <option value="stalled">Stalled</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <select id="dueFilter" class="bg-transparent text-xs font-bold text-slate-600 outline-none cursor-pointer pr-4">
                        <option value="any">Due: Any Time</option>
                        <option value="week">Due This Week</option>
                        <option value="overdue">Overdue</option>
                        <option value="month">Next 30 Days</option>
                    </select>
                </div>

                <div class="h-8 w-[1px] bg-slate-200 hidden md:block"></div>

                <button id="applyFiltersBtn" class="bg-slate-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:shadow-xl hover:shadow-slate-200 active:scale-95 transition-all flex items-center gap-2">
                    <svg id="filterIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span id="btnText">Apply Filters</span>
                </button>
            </div>
        </header>

        <!-- Bento Grid Layout -->
        <div id="dashboardContent" class="grid grid-cols-12 gap-6 lg:gap-8 transition-opacity duration-300">
            
            <!-- Quick Stats: High Contrast -->
            <div class="col-span-12 md:col-span-4 lg:col-span-3 bento-card p-6 rounded-[2.5rem] bg-slate-900 text-white flex flex-col justify-between overflow-hidden relative">
                <div class="relative z-10">
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Team Capacity</p>
                    <h2 class="text-5xl font-black"><span id="statCapacity">92</span><span class="text-indigo-400">%</span></h2>
                </div>
                <div class="mt-8 relative z-10">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-xs font-medium text-slate-400"><span id="overloadCount">8</span> Overloaded Members</span>
                        <span class="text-rose-400 text-xs font-bold">+12% from last wk</span>
                    </div>
                    <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div id="capacityBar" class="bg-indigo-500 h-full w-[92%] rounded-full shadow-[0_0_15px_rgba(99,102,241,0.5)] transition-all duration-1000"></div>
                    </div>
                </div>
                <!-- Abstract BG Pattern -->
                <div class="absolute -right-4 -top-4 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Bottleneck Alert Card -->
            <div class="col-span-12 md:col-span-4 lg:col-span-3 bento-card p-6 rounded-[2.5rem] flex flex-col justify-between">
                <div class="bg-rose-50 w-12 h-12 rounded-2xl flex items-center justify-center text-rose-500 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest">Late Deliverables</h3>
                    <p class="text-4xl font-black text-slate-900 mt-1" id="statLate">14</p>
                    <div class="flex gap-1 mt-3">
                        <div class="h-1 w-8 bg-rose-500 rounded-full"></div>
                        <div class="h-1 w-8 bg-rose-500 rounded-full"></div>
                        <div class="h-1 w-8 bg-rose-200 rounded-full"></div>
                        <div class="h-1 w-8 bg-rose-200 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Throughput Efficiency -->
            <div class="col-span-12 md:col-span-4 lg:col-span-3 bento-card p-6 rounded-[2.5rem] flex flex-col justify-between">
                <div class="bg-emerald-50 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-500 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest">System Velocity</h3>
                    <p class="text-4xl font-black text-slate-900 mt-1" id="statVelocity">78%</p>
                    <p class="text-emerald-600 text-[10px] font-bold mt-2 uppercase">Healthy Throughput</p>
                </div>
            </div>

            <!-- Mini Calendar/Status Bento -->
            <div class="col-span-12 lg:col-span-3 bento-card p-6 rounded-[2.5rem] bg-indigo-600 text-white">
                <h3 class="font-bold text-lg mb-4">Upcoming Sprints</h3>
                <div id="upcomingSprints" class="space-y-3">
                    <div class="flex items-center gap-3 bg-white/10 p-3 rounded-2xl border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-xs font-bold">18</div>
                        <p class="text-xs font-medium">Database Migration</p>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 p-3 rounded-2xl border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-xs font-bold">22</div>
                        <p class="text-xs font-medium">UI Refactor Launch</p>
                    </div>
                </div>
            </div>

            <!-- Main Analytics Section -->
            <div class="col-span-12 lg:col-span-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Progress Distribution -->
                    <div class="bento-card p-8 rounded-[3rem]">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Task Momentum</h3>
                                <p class="text-xs text-slate-400 font-medium">Quarterly distribution of effort</p>
                            </div>
                            <select class="bg-slate-50 border-none text-[10px] font-bold uppercase py-1 px-3 rounded-lg outline-none">
                                <option>2026</option>
                                <option>2025</option>
                            </select>
                        </div>
                        <div id="statusChart" class="h-[280px]"></div>
                    </div>

                    <!-- Priority Health -->
                    <div class="bento-card p-8 rounded-[3rem]">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Priority Mix</h3>
                                <p class="text-xs text-slate-400 font-medium">Urgency vs Capacity allocation</p>
                            </div>
                        </div>
                        <div id="priorityChart" class="h-[280px]"></div>
                    </div>
                </div>

                <!-- Update Progress Interaction Area -->
                <div class="bento-card p-8 rounded-[3rem] bg-gradient-to-br from-indigo-600 to-indigo-800 text-white relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="max-w-sm">
                            <h3 class="text-2xl font-black mb-2">Sync Task Progress</h3>
                            <p class="text-indigo-100/70 text-sm leading-relaxed">
                                Use the <b>PATCH /v1/tasks/{id}</b> protocol to push local progress to the cloud. Real-time updates prevent status meetings.
                            </p>
                        </div>
                        <div class="w-full md:w-64 bg-white/10 backdrop-blur-xl p-6 rounded-3xl border border-white/20 shadow-2xl">
                            <div class="flex justify-between text-xs font-bold mb-3 uppercase tracking-tighter">
                                <span>Active Submission</span>
                                <span class="text-indigo-300">85% Complete</span>
                            </div>
                            <input type="range" class="w-full h-1.5 bg-white/20 rounded-full appearance-none cursor-pointer accent-white">
                            <button class="w-full mt-6 py-3 bg-white text-indigo-600 font-black text-xs uppercase rounded-xl hover:bg-indigo-50 transition-colors">
                                Finalize Push
                            </button>
                        </div>
                    </div>
                    <!-- Abstract Visual Element -->
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/5 rounded-full border border-white/10 group-hover:scale-110 transition-transform duration-1000"></div>
                </div>
            </div>

            <!-- Employee Performance Sidebar -->
            <div class="col-span-12 lg:col-span-4 bento-card p-8 rounded-[3rem]">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-xl font-black text-slate-900">Workload Balance</h3>
                    <div class="p-2 bg-slate-50 rounded-xl">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    </div>
                </div>

                <div id="employeeList" class="space-y-8 overflow-y-auto max-h-[700px] scroll-hide pr-2">
                    <!-- Dynamic Employee Rows -->
                </div>

                <div class="mt-10 p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">AI Recommendation</p>
                    <p class="text-sm font-medium text-slate-600 leading-relaxed mb-4" id="aiAdvice">
                        Sarah and John are at <b>critical capacity</b>. Suggest offloading 2-3 tasks to Marcus to maintain sprint velocity.
                    </p>
                    <button class="w-full bg-white border border-slate-200 text-slate-900 font-bold py-3 rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                        Optimize Resources
                    </button>
                </div>
            </div>

        </div>
    </div>

    @php
        $statusDataGrouped = [
            'Jan' => [12, 18, 10], 'Feb' => [15, 22, 14],
            'Mar' => [10, 25, 18], 'Apr' => [20, 15, 25]
        ];
        $priorityData = [
            ['priority' => 'Critical', 'count' => 12],
            ['priority' => 'High', 'count' => 18],
            ['priority' => 'Routine', 'count' => 8]
        ];
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const font = 'Plus Jakarta Sans, sans-serif';
            let momentumChart, priorityChart;

            // Sample Data Store
            const employees = [
                { id: 'sarah', name: 'Sarah Chen', role: 'Principal Engineer', tasks: 9, color: 'rose' },
                { id: 'marcus', name: 'Marcus T.', role: 'UX Designer', tasks: 4, color: 'indigo' },
                { id: 'elena', name: 'Elena Rodriguez', role: 'DevOps', tasks: 6, color: 'emerald' },
                { id: 'john', name: 'John Wick', role: 'QA Specialist', tasks: 8, color: 'rose' }
            ];

            const chartData = {
                all: {
                    momentum: { backlog: [12, 15, 10, 20], active: [18, 22, 25, 15], closed: [10, 14, 18, 25] },
                    priority: [12, 18, 8],
                    stats: { capacity: 92, overloaded: 8, late: 14, velocity: '78%' }
                },
                sarah: {
                    momentum: { backlog: [2, 1, 3, 2], active: [8, 9, 7, 9], closed: [4, 5, 4, 6] },
                    priority: [6, 4, 1],
                    stats: { capacity: 98, overloaded: 1, late: 5, velocity: '62%' }
                },
                marcus: {
                    momentum: { backlog: [1, 2, 1, 1], active: [3, 4, 3, 4], closed: [5, 6, 7, 8] },
                    priority: [1, 3, 5],
                    stats: { capacity: 45, overloaded: 0, late: 1, velocity: '94%' }
                }
            };

            function renderEmployees(filterId = 'all') {
                const list = document.getElementById('employeeList');
                list.innerHTML = '';
                
                const filtered = filterId === 'all' ? employees : employees.filter(e => e.id === filterId);
                
                filtered.forEach(emp => {
                    const row = `
                        <div class="relative flex items-center gap-5 p-4 rounded-3xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div class="relative">
                                <div class="w-14 h-14 rounded-2xl bg-${emp.color}-100 flex items-center justify-center text-${emp.color}-600 font-black text-xl">
                                    ${emp.name.charAt(0)}
                                </div>
                                ${emp.tasks > 7 ? '<span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 border-2 border-white rounded-full"></span>' : ''}
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-slate-900">${emp.name}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">${emp.role}</p>
                                    </div>
                                    <span class="text-xs font-black text-slate-400">${emp.tasks}/10</span>
                                </div>
                                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full bg-${emp.color}-500 rounded-full transition-all duration-1000" style="width: ${emp.tasks * 10}%"></div>
                                </div>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('beforeend', row);
                });
            }

            function updateDashboard(empId) {
                const data = chartData[empId] || chartData.all;

                // Update Stats
                document.getElementById('statCapacity').innerText = data.stats.capacity;
                document.getElementById('overloadCount').innerText = data.stats.overloaded;
                document.getElementById('statLate').innerText = data.stats.late;
                document.getElementById('statVelocity').innerText = data.stats.velocity;
                document.getElementById('capacityBar').style.width = data.stats.capacity + '%';

                // Update Charts
                momentumChart.updateSeries([
                    { name: 'Backlog', data: data.momentum.backlog },
                    { name: 'Active', data: data.momentum.active },
                    { name: 'Closed', data: data.momentum.closed }
                ]);
                
                priorityChart.updateSeries(data.priority);

                // Update List
                renderEmployees(empId);

                // Update Advice
                const advice = document.getElementById('aiAdvice');
                if(empId === 'sarah') {
                    advice.innerHTML = "<b>Sarah</b> is currently critical. Recommend prioritizing her <b>Database Schema</b> task for the next 48 hours.";
                } else if(empId === 'marcus') {
                    advice.innerHTML = "<b>Marcus</b> has high availability. He is ideally suited to assist <b>Sarah</b> with UI component documentation.";
                } else {
                    advice.innerHTML = "Sarah and John are at <b>critical capacity</b>. Suggest offloading 2-3 tasks to Marcus to maintain sprint velocity.";
                }
            }

            // Init Charts
            momentumChart = new ApexCharts(document.querySelector("#statusChart"), {
                series: [
                    { name: 'Backlog', data: [12, 15, 10, 20] },
                    { name: 'Active', data: [18, 22, 25, 15] },
                    { name: 'Closed', data: [10, 14, 18, 25] }
                ],
                chart: { type: 'bar', height: 280, stacked: true, toolbar: { show: false }, fontFamily: font },
                plotOptions: { bar: { columnWidth: '35%', borderRadius: 10 } },
                colors: ['#f1f5f9', '#6366f1', '#10b981'],
                xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr'], labels: { style: { colors: '#94a3b8' } } },
                yaxis: { show: false }, grid: { show: false }, dataLabels: { enabled: false },
                legend: { position: 'top', horizontalAlign: 'left', fontWeight: 700 }
            });
            momentumChart.render();

            priorityChart = new ApexCharts(document.querySelector("#priorityChart"), {
                series: [12, 18, 8],
                chart: { type: 'donut', height: 280, fontFamily: font },
                labels: ['Critical', 'High', 'Routine'],
                colors: ['#f43f5e', '#fbbf24', '#6366f1'],
                plotOptions: { pie: { donut: { size: '85%' } } },
                dataLabels: { enabled: false },
                legend: { position: 'bottom', fontWeight: 600 }
            });
            priorityChart.render();

            renderEmployees();

            // Filter Interaction
            const applyBtn = document.getElementById('applyFiltersBtn');
            const dashboard = document.getElementById('dashboardContent');

            applyBtn.addEventListener('click', () => {
                const empValue = document.getElementById('employeeFilter').value;
                
                // Visual Feedback
                applyBtn.classList.add('opacity-75');
                applyBtn.innerText = 'Updating...';
                dashboard.classList.add('filter-loading');

                setTimeout(() => {
                    updateDashboard(empValue);
                    
                    applyBtn.classList.remove('opacity-75');
                    applyBtn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Apply Filters
                    `;
                    dashboard.classList.remove('filter-loading');
                }, 600);
            });
        });
    </script>
</body>

</html>