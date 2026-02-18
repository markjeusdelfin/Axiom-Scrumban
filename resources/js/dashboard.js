// Dashboard Monitoring System - JavaScript

const AxiomDB = {
    apiUrl: '/scrumban/dashboard-summary',
    allTeamData: [],
    stats: {},

    validateData(data) {
        if (!data || typeof data !== 'object') {
            console.error('Invalid data structure: data is not an object');
            return false;
        }

        if (!data.success) {
            console.error('API returned success=false:', data.error || 'Unknown error');
            return false;
        }

        if (!data.stats) {
            console.error('Invalid data structure: missing stats');
            return false;
        }

        if (!Array.isArray(data.team)) {
            console.error('Invalid data structure: team is not an array');
            return false;
        }

        // Validate team members have required fields
        const invalidMembers = data.team.filter(m => !m.id || !m.name || typeof m.load !== 'number');
        if (invalidMembers.length > 0) {
            console.warn('Some team members missing required fields:', invalidMembers);
        }

        return true;
    },

    async fetchSummary() {
        try {
            console.log('[API] Fetching from:', this.apiUrl);
            const response = await fetch(this.apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                cache: 'no-cache'
            });

            console.log('[API] Response Status:', response.status, response.statusText);

            if (!response.ok) {
                const errorText = await response.text();
                console.error(`[API] HTTP Error ${response.status}:`, errorText);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('[API] Invalid content type:', contentType, 'Body:', text);
                throw new Error('API did not return JSON');
            }

            const data = await response.json();
            console.log('[API] Parsed JSON:', data);

            if (!this.validateData(data)) {
                throw new Error('Data validation failed');
            }

            // Store data for later use
            this.allTeamData = data.team || [];
            this.stats = data.stats || {};

            console.log('[API] Successfully fetched and validated data');
            return data;
        } catch (error) {
            console.error('[API] Fetch Error:', error.message);
            console.error('[API] Full error:', error);
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
                <div class="flex flex-col items-end gap-1">
                    <span class="text-lg font-bold ${u.load >= 90 ? 'text-red-600' : u.load >= 75 ? 'text-amber-600' : 'text-green-600'}">${Math.round(u.load)}%</span>
                    ${u.load >= 90 ? '<span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded">🚨 Overloaded</span>' : u.load >= 75 ? '<span class="text-xs font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded">⚠️ High Load</span>' : ''}
                </div>
            </div>
            <div class="workload-indicator">
                <div class="workload-bar ${getWorkloadClass(u.load)}" style="width: ${u.load}%"></div>
            </div>
        </div>
    `).join('');
}

async function syncWithPostgres() {
    console.log('[Dashboard] Starting data sync...');
    const loader = document.getElementById('app-loader');
    
    try {
        loader.style.display = 'flex';
        loader.style.opacity = '1';

        const response = await AxiomDB.fetchSummary();

        if (!response || !response.success) {
            throw new Error('API returned no data or success=false');
        }

        console.log('[Dashboard] Data sync successful, updating UI...');
        
        // Safely extract and validate data
        const team = Array.isArray(response.team) ? response.team : [];
        const stats = response.stats || {};
        
        if (team.length === 0) {
            console.warn('[Dashboard] No team members returned from API');
        }

        // Calculate total tasks from team data
        const totalTasks = team.reduce((sum, member) => {
            const count = parseInt(member.task_count) || 0;
            return sum + count;
        }, 0);
        
        console.log('[Dashboard] Total tasks calculated:', totalTasks);

        // Update metrics
        const metricTotal = document.getElementById('metricTotalTasks');
        const metricUtil = document.getElementById('metricUtilization');
        const metricOverdue = document.getElementById('metricOverdue');
        const metricInProgress = document.getElementById('metricInProgress');

        if (metricTotal && metricUtil && metricOverdue && metricInProgress) {
            metricTotal.innerText = totalTasks;
            metricUtil.innerText = (stats.utilization || 0) + '%';
            metricOverdue.innerText = stats.lateTasks || 0;
            
            // Count tasks in progress from team load average
            const avgLoad = team.length > 0 ? team.reduce((sum, m) => sum + (m.load || 0), 0) / team.length : 0;
            const inProgressEstimate = Math.round(totalTasks * (avgLoad / 100) * 0.6) || 0;
            metricInProgress.innerText = inProgressEstimate;
        } else {
            console.error('[Dashboard] Some metric elements not found in DOM');
        }

        // Check for and display bottlenecks
        const overloaded = team.filter(m => m.load >= 90);
        console.log('[Dashboard] Found', overloaded.length, 'overloaded team members');
        
        const bottleneckAlert = document.getElementById('bottleneckAlert');
        
        if (overloaded.length > 0 && filterState.showBottlenecks) {
            const names = overloaded.map(m => m.name).join(', ');
            const message = `${names} ${overloaded.length === 1 ? 'is' : 'are'} overwhelmed with tasks (${overloaded.map(m => m.task_count || 0).join(', ')} tasks each). Consider redistributing work.`;
            document.getElementById('bottleneckMessage').innerText = message;
            bottleneckAlert.classList.remove('hidden');
        } else {
            bottleneckAlert.classList.add('hidden');
        }

        // Update charts with proper validation
        const momentum = Array.isArray(stats.momentum) ? stats.momentum : [0, 0, 0, 0, 0, 0];
        const priorityData = Array.isArray(stats.priorityData) ? stats.priorityData : [0, 0, 0];

        if (charts.momentum && charts.priority) {
            console.log('[Dashboard] Updating charts with momentum:', momentum.slice(-6), 'and priority:', priorityData);
            charts.momentum.updateSeries([{ data: momentum.slice(-6) }]);
            charts.priority.updateSeries(priorityData);
        } else {
            console.error('[Dashboard] Chart objects not initialized');
        }

        // Render workload
        renderWorkload(team);
        console.log('[Dashboard] UI update complete');
        
    } catch (error) {
        console.error('[Dashboard] Sync error:', error.message);

        const errorToast = document.getElementById('error-toast');
        const errorMsg = document.getElementById('error-message');
        if (errorMsg && errorToast) {
            errorMsg.innerText = 'Failed to load dashboard data: ' + (error.message || 'Unknown error');
            errorToast.style.display = 'block';
        }

        // Clear all metrics
        document.getElementById('metricTotalTasks').innerText = '0';
        document.getElementById('metricUtilization').innerText = '0%';
        document.getElementById('metricOverdue').innerText = '0';
        document.getElementById('metricInProgress').innerText = '0';
        document.getElementById('workloadList').innerHTML = '<p class="text-slate-400 text-sm">Error loading data. Check browser console for details.</p>';
    } finally {
        loader.style.opacity = '0';
        setTimeout(() => loader.style.display = 'none', 500);
    }
}

// Initialize event listeners when DOM is ready
function initializeEventListeners() {
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

    const showBottlenecksBtn = document.getElementById('showBottlenecksBtn');
    if (showBottlenecksBtn) {
        showBottlenecksBtn.addEventListener('click', function() {
            filterState.showBottlenecks = !filterState.showBottlenecks;
            this.classList.toggle('active');
            syncWithPostgres();
        });
    }

    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', syncWithPostgres);
    }
}

// Initialize dashboard when window loads
window.addEventListener('load', () => {
    console.log('[Dashboard] Window loaded, initializing...');
    initCharts();
    initializeEventListeners();
    syncWithPostgres();
});
