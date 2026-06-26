<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Email Notification Template') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        emailSubject: {{ json_encode($subject) }},
        emailContent: {{ json_encode($content) }},
        get renderedSubject() {
            let text = this.emailSubject || '';
            text = text.replace(/%status_icon%/g, '❌');
            text = text.replace(/%service%/g, 'Web Server HTTP');
            text = text.replace(/%server%/g, 'Production-01');
            text = text.replace(/%server_ip%/g, '192.168.100.15');
            text = text.replace(/%status%/g, 'DOWN');
            text = text.replace(/%old_status%/g, 'UP');
            text = text.replace(/%date%/g, new Date().toISOString().slice(0, 19).replace('T', ' '));
            return text;
        },
        get renderedContent() {
            let text = this.emailContent || '';
            text = text.replace(/%status_icon%/g, '❌');
            text = text.replace(/%service%/g, 'Web Server HTTP');
            text = text.replace(/%server%/g, 'Production-01');
            text = text.replace(/%server_ip%/g, '192.168.100.15');
            text = text.replace(/%status%/g, 'DOWN');
            text = text.replace(/%old_status%/g, 'UP');
            text = text.replace(/%date%/g, new Date().toISOString().slice(0, 19).replace('T', ' '));
            return text;
        },
        copyPlaceholder(placeholder) {
            navigator.clipboard.writeText(placeholder);
            alert('Copied: ' + placeholder);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Alert -->
            @if ($successMessage)
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm text-green-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ $successMessage }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- EDITOR PANEL (6/12 cols) -->
                <div class="lg:col-span-6 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Edit HTML Email Template</h3>
                    <p class="text-xs text-gray-500 mb-6">
                        Write custom HTML for your alert emails. Click on any placeholder badge below to copy it.
                    </p>

                    <!-- Placeholders List -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Available Placeholders</label>
                        <div class="flex flex-wrap gap-2">
                            <button @click="copyPlaceholder('%status_icon%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %status_icon%
                            </button>
                            <button @click="copyPlaceholder('%service%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %service%
                            </button>
                            <button @click="copyPlaceholder('%server%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %server%
                            </button>
                            <button @click="copyPlaceholder('%server_ip%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %server_ip%
                            </button>
                            <button @click="copyPlaceholder('%status%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %status%
                            </button>
                            <button @click="copyPlaceholder('%old_status%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %old_status%
                            </button>
                            <button @click="copyPlaceholder('%date%')" class="px-2.5 py-1 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %date%
                            </button>
                        </div>
                    </div>

                    <form @submit.prevent="Livewire.find('{{ $this->getId() }}').save(emailSubject, emailContent)" class="space-y-5">
                        <!-- Subject Field -->
                        <div>
                            <label for="subject" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email Subject</label>
                            <input id="subject" 
                                   type="text"
                                   x-model="emailSubject" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm font-semibold text-gray-700"
                                   placeholder="Subject line..."/>
                            @error('subject') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- HTML Content Field -->
                        <div>
                            <label for="content" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">HTML Code</label>
                            <textarea id="content" 
                                      x-model="emailContent" 
                                      rows="16" 
                                      class="w-full font-mono text-xs bg-slate-900 text-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-slate-950 transition-all resize-y"
                                      placeholder="<html>..."></textarea>
                            @error('content') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md shadow-indigo-200 hover:shadow-lg transition-all">
                                {{ __('Save Template') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PREVIEW PANEL (6/12 cols) -->
                <div class="lg:col-span-6 flex flex-col h-full bg-slate-100 rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <!-- Email Client Header Mockup -->
                    <div class="bg-white px-6 py-4 border-b border-gray-200 flex flex-col gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs text-gray-400 ml-2 font-semibold font-mono">Mail Client Preview</span>
                        </div>
                        
                        <div class="mt-2 space-y-1.5 text-xs">
                            <div class="flex border-b border-gray-100 pb-1.5">
                                <span class="w-16 text-gray-400 font-semibold">Subject:</span>
                                <span class="text-gray-800 font-bold" x-text="renderedSubject"></span>
                            </div>
                            <div class="flex border-b border-gray-100 pb-1.5">
                                <span class="w-16 text-gray-400 font-semibold">From:</span>
                                <span class="text-gray-600 font-medium">Statify Alert &lt;service@rcproject.it&gt;</span>
                            </div>
                            <div class="flex">
                                <span class="w-16 text-gray-400 font-semibold">To:</span>
                                <span class="text-gray-600 font-medium">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Email Sandboxed Iframe Preview -->
                    <div class="p-4 bg-slate-100 flex-1 flex flex-col min-h-[520px]">
                        <iframe 
                            class="w-full flex-1 min-h-[500px] border border-gray-200 rounded-xl bg-white shadow-sm"
                            x-ref="previewIframe"
                            x-effect="
                                $nextTick(() => {
                                    if ($refs.previewIframe) {
                                        const doc = $refs.previewIframe.contentDocument;
                                        if (doc) {
                                            doc.open();
                                            doc.write(renderedContent);
                                            doc.close();
                                        }
                                    }
                                });
                            "
                        ></iframe>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
