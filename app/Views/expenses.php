<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div x-data="expenseApp(<?= htmlspecialchars(json_encode($expenses)) ?>)" class="space-y-6">

    <div class="flex justify-end mb-4">
        <button @click="showModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:scale-105">
            + Yeni Gider Ekle
        </button>
    </div>

    <div x-show="showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        @keydown.escape.window="showModal = false">
        <div @click.away="showModal = false"
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border">

            <div class="p-6 border-b flex justify-between items-center bg-slate-50">
                <h3 class="text-xl font-bold text-slate-800">Yeni Gider Ekle</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="<?= base_url('expenses/create') ?>" method="post" class="p-6 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Harcama Başlığı</label>
                    <input type="text" name="title" required placeholder="Örn: Market Alışverişi"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tutar (₺)</label>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="category" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <option value="Gıda">Gıda</option>
                            <option value="Eğlence">Eğlence</option>
                            <option value="Ulaşım">Ulaşım</option>
                            <option value="Fatura">Fatura</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tarih</label>
                    <input type="date" name="expense_date" required value="<?= date('Y-m-d') ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showModal = false" class="flex-1 px-4 py-2 border rounded-lg hover:bg-slate-50 font-medium transition">İptal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold transition">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-2xl shadow-md border-l-4 border-indigo-500">
            <p class="text-sm text-slate-500 font-medium">Toplam Harcama</p>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">
                ₺<span x-text="totalAmount()"></span>
            </h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-sm text-slate-500 font-medium">İşlem Adedi</p>
            <h2 class="text-3xl font-bold text-slate-800" x-text="expenses.length"></h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-amber-500">
            <p class="text-sm text-slate-500 font-medium">En Büyük Harcama</p>
            <h2 class="text-3xl font-bold text-slate-800">
                ₺<span x-text="maxExpense()"></span>
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
</div>

<script>
    function expenseApp(initialData) {
        return {
            expenses: initialData,
            search: "",
            showModal: false, // Modal'ın açık/kapalı durumunu tutar

            // Toplam tutarını hesaplayan fonksiyon
            totalAmount() {
                return this.expenses
                    .reduce((sum, item) => sum + parseFloat(item.amount), 0)
                    .toLocaleString('tr-TR', {
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
                    'Ulaşım': 'bg-blue-100 text-blue-700',
                    'Fatura': 'bg-orange-100 text-orange-700',
                    'Diğer': 'bg-slate-100 text-slate-700'
                };
                return colors[cat] || 'bg-gray-100';
            }
        }
    }
</script>

<?= $this->endSection() ?>