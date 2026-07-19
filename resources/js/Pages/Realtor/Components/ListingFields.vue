<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-6">
        <div class="sm:col-span-2">
            <label for="beds" class="label">Beds</label>
            <input id="beds" v-model.number="form.beds" type="number" min="0" class="input" />
            <div v-if="form.errors.beds" class="input-error">{{ form.errors.beds }}</div>
        </div>

        <div class="sm:col-span-2">
            <label for="baths" class="label">Baths</label>
            <input id="baths" v-model.number="form.baths" type="number" min="0" class="input" />
            <div v-if="form.errors.baths" class="input-error">{{ form.errors.baths }}</div>
        </div>

        <div class="sm:col-span-2">
            <label for="area" class="label">Area (m²)</label>
            <input id="area" v-model.number="form.area" type="number" min="0" class="input" />
            <div v-if="form.errors.area" class="input-error">{{ form.errors.area }}</div>
        </div>

        <div class="sm:col-span-4">
            <label for="city" class="label">City</label>
            <input id="city" v-model="form.city" type="text" class="input" />
            <div v-if="form.errors.city" class="input-error">{{ form.errors.city }}</div>
        </div>

        <div class="sm:col-span-2">
            <label for="code" class="label">Post code</label>
            <input id="code" v-model="form.code" type="text" class="input" />
            <div v-if="form.errors.code" class="input-error">{{ form.errors.code }}</div>
        </div>

        <div class="sm:col-span-4">
            <label for="street" class="label">Street</label>
            <input id="street" v-model="form.street" type="text" class="input" />
            <div v-if="form.errors.street" class="input-error">{{ form.errors.street }}</div>
        </div>

        <div class="sm:col-span-2">
            <label for="street_nr" class="label">Street no.</label>
            <input id="street_nr" v-model="form.street_nr" type="text" class="input" />
            <div v-if="form.errors.street_nr" class="input-error">{{ form.errors.street_nr }}</div>
        </div>

        <div class="sm:col-span-6">
            <label for="price" class="label">Price (USD)</label>
            <input id="price" v-model.number="form.price" type="number" min="0" class="input" />
            <div v-if="form.errors.price" class="input-error">{{ form.errors.price }}</div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    form: Object,
});
</script>
