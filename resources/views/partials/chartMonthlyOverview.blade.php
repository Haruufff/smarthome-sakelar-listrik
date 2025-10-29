<div class="card">
    <div class="pt-5 flex justify-between items-center">
        <h1 class="header-1">Monthly Overview</h1>
        <div>
            <select id="chartTypeSelector" class="w-50 px-4 py-1 border border-gray-300 rounded-lg bg-white cursor-pointer">
                <option value="energy">Average Energy</option>
                <option value="price">Total Price (Rp)</option>
            </select>
        </div>
    </div>
    <div class="relative w-full" style="min-height: 320px;">
        <div id="MonthlyAverageChart" class="chart-container w-full transition-opacity duration-300 opacity-100"></div>
        <div id="MonthlyPriceChart" class="chart-container w-full transition-opacity duration-300 opacity-0 hidden"></div>
    </div>
</div>