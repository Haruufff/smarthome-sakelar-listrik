<div id="taxesModal" class="modal z-10 opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

    <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <div class="modal-content py-4 text-left px-6">
            <div class="flex justify-between items-center pb-3">
                <span class="text-2xl font-bold">Electric Power</span>
                <div class="modal-close cursor-pointer z-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>

            <div class="modal-body">
                <div id="taxes-alert-message" class="mb-4"></div>
                <form action="#" method="POST" id="updateTaxesForm">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" id="taxes-id" name="taxes_id" value="">

                    <div class="mb-2">
                        <label for="category_tax_id" class="block mb-2 text-sm font-medium text-gray-900">Tax</label>
                        <select name="category_tax_id" id="category-tax-id" class="px-3 w-full py-2 border border-gray-300 rounded-md">
                            @foreach ($categoryTaxes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-red-500 text-xs italic" id="taxes-error"></span>
                    </div>

                    <div class="flex justify-end pt-2 gap-2">
                        <button type="button" data-modal-close="taxesModal" class="bg-gray-300 text-gray-800 py-2 px-5 rounded-md hover:bg-gray-400">Cancel</button>
                        <button type="submit" data-action="update-taxes" class="bg-[#5d87ff] text-white font-semibold py-2 px-5 rounded-md hover:bg-[#85a4f8] cursor-pointer">Save Changes</button>
                    </div>
                </form>
            </div>
    </div>
</div>