<!-- Global Action Confirmation Modal (Premium Theme Matched) -->
<div x-data="{ 
        title: 'Are you sure?', 
        message: '', 
        confirmText: 'Yes, delete', 
        cancelText: 'Cancel', 
        onConfirm: null 
     }" 
     x-on:open-confirm-modal.window="
        title = $event.detail.title || 'Are you sure?'; 
        message = $event.detail.message || ''; 
        confirmText = $event.detail.confirmText || 'Yes, delete'; 
        cancelText = $event.detail.cancelText || 'Cancel';
        onConfirm = $event.detail.onConfirm;
        $dispatch('open-modal', { name: 'confirm-modal-box' });
     "
     x-cloak>
     
     <x-ui.modal name="confirm-modal-box" maxWidth="lg">
         <x-ui.card padding="p-0" rounded="rounded-none" class="border-0 shadow-none bg-transparent text-center space-y-6">
             <!-- Warning/Danger Premium Icon -->
             <div class="mx-auto h-16 w-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 border border-red-100">
                 <x-heroicon-o-exclamation-triangle class="h-8 w-8" />
             </div>

             <!-- Header Info -->
             <div class="space-y-2">
                 <h3 class="text-xl font-bold text-gray-800 tracking-tight" x-text="title"></h3>
                 <p class="text-sm font-semibold text-gray-400 leading-relaxed uppercase tracking-wider" x-text="message"></p>
             </div>

             <!-- Action Buttons -->
             <div class="grid grid-cols-2 gap-3 pt-2">
                 <x-button type="button" @click="$dispatch('close-modal', { name: 'confirm-modal-box' })" variant="secondary-outline">
                     <span x-text="cancelText"></span>
                 </x-button>
                 <x-button type="button" 
                     @click="
                         if (onConfirm) onConfirm();
                         $dispatch('close-modal', { name: 'confirm-modal-box' });
                     "
                     variant="danger"
                     class="shadow-lg shadow-red-600/10">
                     <span x-text="confirmText"></span>
                 </x-button>
             </div>
         </x-ui.card>
     </x-ui.modal>
</div>
