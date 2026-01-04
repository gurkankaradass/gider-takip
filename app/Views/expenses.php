<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white p-6 rounded-2xl shadow-md border-l-4 border-indigo-500">
        <p class="text-sm text-slate-500 font-medium">Toplam Harcama</p>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">
            ₺<span x-text="totalAmount()"></span>
        </h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-500">
        <p class="text-sm text-slate-500 font-medium">İşlem Adedi</p>
        <h2 class="text-3xl font-bold text-slate-800" x-text="expenses.lenght"></h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500">
        <p class="text-sm text-slate-500 font-medium">En Büyük Harcama</p>
        <h2 class="text-3xl font-bold text-slate-800">
            ₺<span x-text="matExpense()"></span>
        </h2>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm borderr overflow-hidden">
    <div class="p-4 border-b bg-slate-50/50 flex flex-col md:flex-row justify-between gap-4">
        <h3 class="font-bold text-slate-700 self-center">Harcama Geçmişi</h3>
        <input type="text" x-model="search" placeholder="Harcama ara..."
            class="border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-full md:w-64">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-3">Açıklama</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3 text-right">Tutar</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <template x-for="item in filteredExpenses" :key="item.id">
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800" x-text="item.title"></div>
                            <div class="text-xs text-slate-400" x-text="item.expense_date"></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                                :class="categoryClass(item.category)" x-text="item.category"></span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                            ₺<span x-text="parseFloat(item.amount).toLocaleString('tr-TR')"></span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

<script>
    function expenseApp(initialData) {
        return {
            expenses: initialData,
            search: "",

            // Toplam tutarını hesaplayan fonksiyon
            totalAmount() {
                return this.expenses.
                reduce((sum, item) => sum + parseFloat(item.amount), 0)
                    .toLocalString('tr-TR', {
                        minimumFractionDigits: 2
                    });
            },

            // En büyük harcamayı bulma
            maxExpense() {
                if (this.expenses.length === 0) return '0';
                return Math.max(...this.expenses.map(item => item.amount))
                    .toLocaleString('tr-TR');
            },

            // Arama Filtresi
            get filteredExpenses() {
                return this.expenses.filter(i => i.title.toLowerCase().includes(this.search.toLowerCase()));
            },

            // Kategoriye göre renk döndürme
            categoryClass(cat) {
                const colors = {
                    'Gıda': 'bg-red-100 text-red-700',
                    'Eğlence': 'bg-purple-100 text-purple-700',
                    'Ulaşım': 'bg-blue-100 text-blue-100',
                    'Fatura': 'bg-orange-100 text-orange-100',
                    'Diğer': 'bg-slate-100 text-slate-700'
                };
                return colors[cat] || 'bg-gray-100';
            }
        }
    }
</script>

<?= $this->endSection() ?>