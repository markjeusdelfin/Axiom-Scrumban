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
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .scroll-hide::-webkit-scrollbar { width: 4px; }
        .scroll-hide::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>

<body class="bg-[#F8FAFC] min-h-screen text-slate-800 antialiased">

    <div class="max-w-[1600px] mx-auto p-4 md:p-8">

        <!-- Managerial Header -->
        <header class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-rose-600 px-2 py-0.5 rounded text-[10px] font-bold text-white uppercase tracking-wider">Manager View</span>
                    <span class="text-slate-400 text-sm font-semibold">/ Resource Planning</span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Workload & Bottleneck Oversight</h1>
                <p class="text-slate-500 font-medium">Identify overcapacity employees and stalled projects in real-time.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="glass-card flex items-center gap-3 p-1.5 rounded-2xl shadow-sm border-slate-200">
                    <div class="flex items-center px-4 py-2 bg-white rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase mr-3">Team</span>
                        <select class="outline-none bg-transparent text-sm font-bold text-slate-700 cursor-pointer">
                            <option>Engineering Team A</option>
                            <option>Design Studio</option>
                        </select>
                    </div>
                    <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-100">
                        Generate Report
                    </button>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-12 gap-8">

            <!-- LEFT COLUMN: Critical Managerial Stats -->
            <div class="col-span-12 lg:col-span-8 space-y-8">

                <!-- Management Alerts -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Overloaded Alert -->
                    <div class="bg-white p-6 rounded-[2rem] border-l-4 border-rose-500 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-rose-50 p-3 rounded-2xl text-rose-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded">CRITICAL</span>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900">
                            {{ isset($employees) ? $employees->where('active_tasks', '>', 8)->count() : 2 }}
                        </h4>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Overloaded Members</p>
                    </div>

                    <!-- Delayed Tasks -->
                    <div class="bg-white p-6 rounded-[2rem] border-l-4 border-amber-500 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-amber-50 p-3 rounded-2xl text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded">WARNING</span>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900">14</h4>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Late/Stalled Tasks</p>
                    </div>

                    <!-- Capacity Health -->
                    <div class="bg-indigo-600 p-6 rounded-[2rem] text-white shadow-xl shadow-indigo-200">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-white/20 p-3 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black">78%</h4>
                        <p class="text-indigo-100/70 text-xs font-bold uppercase tracking-widest mt-1">Team Efficiency</p>
                    </div>
                </div>

                <!-- Main Analysis Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Progress Distribution (Grouped Bar Chart) -->
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Task Progress Distribution</h3>
                                <p class="text-xs text-slate-400 font-medium">Comparison across active quarters (2026)</p>
                            </div>
                        </div>
                        <div id="statusChart" class="h-[250px]"></div>
                    </div>

                    <!-- Workload Breakdown -->
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Task Priority Health</h3>
                                <p class="text-xs text-slate-400 font-medium">Are we focusing on the right things?</p>
                            </div>
                        </div>
                        <div id="priorityChart" class="h-[250px]"></div>
                    </div>
                </div>

                <!-- Recent Late Tasks Table -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-6">Flagged Stalled Tasks</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                    <th class="pb-4">Task Name</th>
                                    <th class="pb-4">Assigned To</th>
                                    <th class="pb-4">Days Stalled</th>
                                    <th class="pb-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-slate-600">
                                <tr class="border-b border-slate-50">
                                    <td class="py-4 font-bold text-slate-900">API Integration Refactor</td>
                                    <td class="py-4">Alex Rivera</td>
                                    <td class="py-4 text-rose-500 font-bold">5 Days</td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 bg-amber-50 text-amber-600 rounded-md text-[10px] font-bold uppercase">Pending Review</span>
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-50">
                                    <td class="py-4 font-bold text-slate-900">Database Migration</td>
                                    <td class="py-4">Sarah Chen</td>
                                    <td class="py-4 text-rose-500 font-bold">3 Days</td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md text-[10px] font-bold uppercase">In Progress</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Employee Workload Management -->
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm h-full flex flex-col">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-900">Workload Oversight</h3>
                        <p class="text-xs font-medium text-slate-400 mt-1">Identify and reassign to avoid burnout.</p>
                    </div>

                    <div class="space-y-6 flex-grow overflow-y-auto scroll-hide pr-2">
                        @if(isset($employees) && count($employees) > 0)
                            @foreach($employees as $employee)
                            <div class="p-4 rounded-2xl transition-all border border-transparent hover:border-slate-100 hover:bg-slate-50/50">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-400 text-sm">
                                            {{ substr($employee->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 leading-tight">{{ $employee->name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Capacity: {{ $employee->active_tasks }}/8</p>
                                        </div>
                                    </div>
                                    @if($employee->active_tasks > 7)
                                        <span class="bg-rose-100 text-rose-600 text-[9px] font-black px-2 py-1 rounded">OVERLOAD</span>
                                    @endif
                                </div>
                                
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    @php 
                                        $percent = min(($employee->active_tasks / 10) * 100, 100); 
                                        $isOver = $employee->active_tasks > 7;
                                    @endphp
                                    <div class="h-full transition-all duration-1000 {{ $isOver ? 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]' : 'bg-indigo-500' }}" 
                                         style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Placeholder for preview if no employees passed -->
                            <div class="text-center py-10 opacity-50">
                                <p class="text-sm font-medium">No team data available</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8">
                        <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-100/50 mb-6">
                            <p class="text-indigo-600 font-bold text-sm mb-1">Manager Insight</p>
                            <p class="text-xs text-indigo-900/60 leading-relaxed font-medium">
                                Red bars indicate members with >7 active tasks. Consider reassigning their "Low Priority" tasks to improve team velocity.
                            </p>
                        </div>
                        <button class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-2 group shadow-xl shadow-slate-200">
                            Rebalance Workload
                            <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @php
        // Updated data specifically for the 2026 timeframe
        $statusDataGrouped = $statusCountsGrouped ?? [
            'Q1 2026' => [12, 18, 10],
            'Q2 2026' => [15, 22, 14],
            'Q3 2026' => [10, 25, 18],
            'Q4 2026' => [20, 15, 25]
        ];
        
        $priorityData = $priorityCounts ?? [
            ['priority' => 'High', 'count' => 10],
            ['priority' => 'Med', 'count' => 15],
            ['priority' => 'Low', 'count' => 5]
        ];
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const font = 'Plus Jakarta Sans, sans-serif';

            const statusData = @json($statusDataGrouped);
            const priorityCounts = @json($priorityData);

            // Grouped Bar Chart (Updated for 2026)
            new ApexCharts(document.querySelector("#statusChart"), {
                series: [
                    { name: 'Planned', data: Object.values(statusData).map(v => v[0]) },
                    { name: 'In Progress', data: Object.values(statusData).map(v => v[1]) },
                    { name: 'Completed', data: Object.values(statusData).map(v => v[2]) }
                ],
                chart: { 
                    type: 'bar', 
                    height: 250, 
                    toolbar: { show: false }, 
                    fontFamily: font,
                    sparkline: { enabled: false }
                },
                plotOptions: { 
                    bar: { 
                        horizontal: false,
                        columnWidth: '65%',
                        borderRadius: 2,
                        dataLabels: { position: 'top' }
                    } 
                },
                colors: ['#00a896', '#023e7d', '#70e000'],
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: Object.keys(statusData),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#64748b', fontWeight: 600 } }
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8' } }
                },
                fill: { opacity: 1 },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } },
                    xaxis: { lines: { show: false } }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '10px',
                    fontWeight: 700,
                    markers: { radius: 12 }
                },
                tooltip: { y: { formatter: (val) => val + " Tasks" } }
            }).render();

            // Priority Health Donut
            new ApexCharts(document.querySelector("#priorityChart"), {
                series: priorityCounts.map(i => i.count),
                chart: { type: 'donut', height: 250, fontFamily: font },
                labels: priorityCounts.map(i => i.priority),
                colors: ['#f43f5e', '#fbbf24', '#6366f1'],
                stroke: { width: 0 },
                plotOptions: { 
                    pie: { 
                        donut: { 
                            size: '75%', 
                            labels: { 
                                show: true, 
                                total: { show: true, label: 'Urgency', color: '#94a3b8', fontSize: '11px', fontWeight: 700 },
                                value: { fontSize: '24px', fontWeight: 800, color: '#1e293b' }
                            } 
                        } 
                    } 
                },
                legend: { position: 'bottom', markers: { radius: 12 } }
            }).render();
        });
    </script>
</body>

</html>