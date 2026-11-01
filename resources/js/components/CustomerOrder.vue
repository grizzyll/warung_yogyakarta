<template>
  <div class="max-w-md mx-auto pb-6">
    <header class="bg-[#B91C1C] text-white p-4 sticky top-0 z-10">
      <div class="text-xs opacity-80">Warung Lesehan Yogyakarta</div>
      <div class="text-lg font-bold">Meja {{ table.number }}</div>
    </header>

    <div v-for="(items, category) in grouped" :key="category" class="px-4 mt-4">
      <h2 class="font-bold text-gray-700 mb-2">{{ category }}</h2>
      <div
        v-for="p in items"
        :key="p.id"
        class="bg-white rounded-xl p-3 mb-2 shadow-sm flex justify-between gap-3"
      >
        <span class="text-sm font-medium">{{ p.name }}</span>
        <span class="text-sm font-bold text-[#B91C1C] whitespace-nowrap">{{ rupiah(p.price) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    table: { type: Object, required: true },
    products: { type: Array, required: true },
  },
  computed: {
    grouped() {
      return this.products.reduce((acc, p) => {
        (acc[p.category] ||= []).push(p);
        return acc;
      }, {});
    },
  },
  methods: {
    rupiah(n) {
      return 'Rp ' + Number(n).toLocaleString('id-ID');
    },
  },
};
</script>