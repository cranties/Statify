<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Telegram Notification Template') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        telegramContent: {{ json_encode($content) }},
        get parsedContent() {
            let text = this.telegramContent || '';
            // Escape HTML to prevent injection in preview
            text = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
            
            // Apply mock values for placeholders
            text = text.replace(/%status_icon%/g, '❌');
            text = text.replace(/%service%/g, 'Web Server HTTP');
            text = text.replace(/%server%/g, 'Production-01');
            text = text.replace(/%server_ip%/g, '192.168.100.15');
            text = text.replace(/%status%/g, 'DOWN');
            text = text.replace(/%old_status%/g, 'UP');
            text = text.replace(/%date%/g, new Date().toISOString().slice(0, 19).replace('T', ' '));
            
            // Format Telegram markdown
            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\*(.*?)\*/g, '<strong>$1</strong>');
            text = text.replace(/__(.*?)__/g, '<u>$1</u>');
            text = text.replace(/_(.*?)_/g, '<em>$1</em>');
            text = text.replace(/~(.*?)~/g, '<del>$1</del>');
            text = text.replace(/`([^`]+)`/g, '<code class=\'bg-black/10 px-1 py-0.5 rounded text-pink-600 font-mono text-xs\'>$1</code>');
            text = text.replace(/```([\s\S]*?)```/g, '<pre class=\'bg-black/10 p-2 rounded text-pink-600 font-mono text-xs my-1 whitespace-pre-wrap\'>$1</pre>');
            
            // Newlines
            text = text.replace(/\n/g, '<br>');
            return text;
        },
        copyPlaceholder(placeholder) {
            navigator.clipboard.writeText(placeholder);
            // Flash notification or visual feedback
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
                
                <!-- EDITOR PANEL (7/12 cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Edit Template</h3>
                    <p class="text-xs text-gray-500 mb-6">
                        Use Telegram-supported Markdown to style your messages. Click on any placeholder badge below to copy it.
                    </p>

                    <!-- Placeholders List -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Available Placeholders</label>
                        <div class="flex flex-wrap gap-2">
                            <button @click="copyPlaceholder('%status_icon%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %status_icon% <span class="text-gray-400 font-normal ml-1">(✅/❌)</span>
                            </button>
                            <button @click="copyPlaceholder('%service%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %service% <span class="text-gray-400 font-normal ml-1">(Name)</span>
                            </button>
                            <button @click="copyPlaceholder('%server%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %server% <span class="text-gray-400 font-normal ml-1">(Host)</span>
                            </button>
                            <button @click="copyPlaceholder('%server_ip%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %server_ip% <span class="text-gray-400 font-normal ml-1">(IP)</span>
                            </button>
                            <button @click="copyPlaceholder('%status%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %status% <span class="text-gray-400 font-normal ml-1">(UP/DOWN)</span>
                            </button>
                            <button @click="copyPlaceholder('%old_status%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %old_status% <span class="text-gray-400 font-normal ml-1">(Prev)</span>
                            </button>
                            <button @click="copyPlaceholder('%date%')" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 transition-all">
                                %date% <span class="text-gray-400 font-normal ml-1">(Time)</span>
                            </button>
                        </div>
                    </div>

                    <form @submit.prevent="Livewire.find('{{ $this->getId() }}').save(telegramContent)">
                        <div class="mb-6">
                            <label for="content" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Message Body</label>
                            <textarea id="content" 
                                      x-model="telegramContent" 
                                      rows="12" 
                                      class="w-full font-mono text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y"
                                      placeholder="Write your Telegram template here..."></textarea>
                            @error('content') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                            <span class="text-xs text-gray-400">
                                Markdown support: *bold*, _italic_, `code`, ```codeblock```
                            </span>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-md shadow-indigo-200 hover:shadow-lg transition-all">
                                {{ __('Save Template') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PREVIEW PANEL (5/12 cols) -->
                <div class="lg:col-span-5 flex flex-col h-full">
                    <div class="bg-white rounded-t-2xl px-6 py-4 border-b border-gray-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center text-white font-bold shadow-md shadow-sky-100">
                                TG
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-800">Telegram Client</h4>
                                <span class="text-xs text-green-500 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Live Mockup
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Telegram Chat Window -->
                    <div class="flex-1 min-h-[480px] bg-[#7199ba] p-6 rounded-b-2xl shadow-inner flex flex-col justify-end relative overflow-hidden">
                        <!-- Telegram Wallpaper Pattern Effect -->
                        <div class="absolute inset-0 opacity-[0.03] pointer-events-none bg-repeat" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2280%22 viewBox=%220 0 80 80%22%3E%3Cg fill=%22none%22 stroke=%22%23000%22 stroke-width=%221%22%3E%3Cpath d=%22M15 15l2 2-2-2zm10 10l2 2-2-2zm10 10l2 2-2-2zm10 10l2 2-2-2zm10 10l2 2-2-2z%22/%3E%3C/g%3E%3C/svg%3E');"></div>
                        
                        <!-- System Date Stamp -->
                        <div class="self-center bg-black/10 text-white rounded-full px-3 py-1 text-[11px] font-semibold mb-4 backdrop-blur-sm z-10">
                            Today
                        </div>

                        <!-- Message Bubble -->
                        <div class="self-start bg-white text-gray-800 rounded-2xl rounded-tl-none px-4 py-2.5 max-w-[85%] shadow-md relative flex flex-col z-10 animate-fade-in">
                            <!-- Sender name -->
                            <span class="text-xs font-bold text-sky-600 mb-1">Statify Alert Bot</span>
                            
                            <!-- Content -->
                            <div class="text-[14.5px] leading-relaxed text-gray-800 whitespace-pre-wrap break-all" 
                                 x-html="parsedContent"></div>
                            
                            <!-- Timestamp & Status -->
                            <span class="self-end text-[10px] text-gray-400 mt-1.5 flex items-center gap-0.5">
                                <span x-text="new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                <!-- Double check icons -->
                                <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>

                            <!-- Bubble Tail -->
                            <div class="absolute top-0 -left-2.5 w-3.5 h-4 bg-white" style="clip-path: polygon(100% 0, 100% 100%, 0 0);"></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
