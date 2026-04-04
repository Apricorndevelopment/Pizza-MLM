@extends(Auth::user()->is_vendor === 1 ? 'vendorlayouts.layout' : 'userlayouts.layouts')
@section('title', 'Transfer Funds')

@section('container')
    <div class="min-h-screen bg-gray-50 py-6 font-sans">
        <div class="px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Transfer Funds</h1>
                <p class="text-gray-500 text-sm">Transfer your wallet balances securely.</p>
            </div>

            {{-- Alerts with Close Button --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center justify-between gap-3 text-green-800 shadow-sm transition-all duration-300" id="success-alert">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-check-circle-fill text-xl text-green-500"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="document.getElementById('success-alert').remove()" class="text-green-500 hover:text-green-800 transition-colors">
                        <i class="bi bi-x-lg font-bold"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center justify-between gap-3 text-red-800 shadow-sm transition-all duration-300" id="error-alert">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-exclamation-circle-fill text-xl text-red-500"></i>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                    <button type="button" onclick="document.getElementById('error-alert').remove()" class="text-red-500 hover:text-red-800 transition-colors">
                        <i class="bi bi-x-lg font-bold"></i>
                    </button>
                </div>
            @endif

            {{-- Tabs Control --}}
            <div class="flex p-1 bg-gray-200 rounded-xl mb-6 w-full sm:w-max">
                <button onclick="switchFundTab('wallet1')" id="btn-w1" class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm">
                    Main Wallet (W1)
                </button>
                <button onclick="switchFundTab('wallet2')" id="btn-w2" class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 text-gray-500 hover:text-gray-700">
                    Second Wallet (W2)
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Form Section --}}
                <div class="md:col-span-2 space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                        <form id="mainTransferForm" method="POST" action="{{ route('user.transfer.wallet1') }}">
                            @csrf
                            <input type="hidden" name="wallet_type" id="wallet_type_input" value="wallet1">

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2" id="recipient_label">Recipient ULID (Downline Only)</label>
                                <div class="flex gap-2">
                                    <input type="text" name="ulid" id="target_ulid" required
                                        class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                        placeholder="Enter Downline ULID">
                                    <button type="button" id="verifyBtn" class="bg-gray-800 text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-black transition-all">
                                        Verify
                                    </button>
                                </div>
                            </div>

                            {{-- Receiver Detail --}}
                            <div id="receiverBox" class="hidden mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100">
                                <h5 class="text-blue-800 font-bold text-sm" id="res_name"></h5>
                                <p class="text-blue-600 text-xs mt-1" id="res_email"></p>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Amount to Transfer</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-400 font-bold">₹</span>
                                    <input type="number" name="wallet1" id="amount_input" min="1" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <button type="submit" id="finalTransferBtn" disabled
                                class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 disabled:bg-gray-300 disabled:shadow-none transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-send-fill"></i> Confirm Transfer
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Balance Side Cards --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Your Balance</p>
                        <h3 class="text-3xl font-black text-gray-900" id="display_balance">₹{{ number_format(Auth::user()->wallet1_balance) }}</h3>
                        <p class="text-[10px] text-blue-600 font-bold mt-2 uppercase" id="display_label">Main Wallet (W1)</p>
                    </div>

                    <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-xl shadow-blue-100">
                        <i class="bi bi-shield-check text-2xl"></i>
                        <h4 class="font-bold mt-4 text-sm uppercase tracking-wider">Secure Transfer</h4>
                        <p class="text-blue-100 text-xs mt-2 leading-relaxed" id="security_info">Funds from W1 can only be sent to your downline network. Verification is required.</p>
                    </div>
                </div>
            </div>

            {{-- Direct Downline Table --}}
            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" id="downlineTableSection">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Quick Select Downline</h3>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ count($downlineUsers) }} Directs</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] uppercase text-gray-400 font-bold border-b border-gray-100">
                                <th class="px-6 py-4">Member</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($downlineUsers as $dUser)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-800">{{ $dUser->name }}</span>
                                            <span class="text-[11px] text-gray-400 font-mono">{{ $dUser->ulid }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-600">{{ $dUser->email }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button"
                                            onclick="selectDownline('{{ $dUser->ulid }}', '{{ $dUser->name }}', '{{ $dUser->email }}')"
                                            class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Load current balances from Auth
        let wallet1Bal = {{ Auth::user()->wallet1_balance }};
        let wallet2Bal = {{ Auth::user()->wallet2_balance }};
        const routeW1 = "{{ route('user.transfer.wallet1') }}";
        const routeW2 = "{{ route('user.transfer.wallet2') }}";

        function switchFundTab(type) {
            const btn1 = document.getElementById('btn-w1');
            const btn2 = document.getElementById('btn-w2');
            const inputName = document.getElementById('amount_input');
            const form = document.getElementById('mainTransferForm');
            const balanceH2 = document.getElementById('display_balance');
            const labelP = document.getElementById('display_label');
            const typeInput = document.getElementById('wallet_type_input');
            const targetUlid = document.getElementById('target_ulid');
            const recipientLabel = document.getElementById('recipient_label');
            const securityInfo = document.getElementById('security_info');

            if (type === 'wallet1') {
                btn1.className = "flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm";
                btn2.className = "flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 text-gray-500 hover:text-gray-700";
                
                inputName.name = "wallet1";
                form.action = routeW1;
                balanceH2.innerText = "₹" + wallet1Bal.toLocaleString();
                labelP.innerText = "Main Wallet (W1)";
                typeInput.value = "wallet1";
                
                targetUlid.placeholder = "Enter Downline ULID";
                recipientLabel.innerText = "Recipient ULID (Downline Only)";
                securityInfo.innerText = "Funds from W1 can only be sent to your downline network. Verification is required.";
            } else {
                btn2.className = "flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm";
                btn1.className = "flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 text-gray-500 hover:text-gray-700";
                
                inputName.name = "wallet2";
                form.action = routeW2;
                balanceH2.innerText = "₹" + wallet2Bal.toLocaleString();
                labelP.innerText = "Second Wallet (W2)";
                typeInput.value = "wallet2";
                
                targetUlid.placeholder = "Enter Any User ULID";
                recipientLabel.innerText = "Recipient ULID (Any User)";
                securityInfo.innerText = "Funds from W2 can be sent to anyone on the platform. Verification is required.";
            }

            // Reset form on tab switch
            hideReceiver();
            targetUlid.value = '';
            inputName.value = '';
        }

        function selectDownline(ulid, name, email) {
            document.getElementById('target_ulid').value = ulid;
            showReceiver(name, email, ulid);
        }

        function showReceiver(name, email, ulid) {
            const box = document.getElementById('receiverBox');
            document.getElementById('res_name').innerText = name + ' (' + ulid + ')';
            document.getElementById('res_email').innerText = email;
            box.classList.remove('hidden');
            document.getElementById('finalTransferBtn').disabled = false;
        }

        function hideReceiver() {
            document.getElementById('receiverBox').classList.add('hidden');
            document.getElementById('finalTransferBtn').disabled = true;
        }

        // Verify Button Event Listener Update
        document.getElementById('verifyBtn').addEventListener('click', function() {
            const ulidInput = document.getElementById('target_ulid').value.trim();
            const currentWalletType = document.getElementById('wallet_type_input').value;

            if (!ulidInput) return alert('Please enter a ULID');

            const verifyBtn = this;
            verifyBtn.disabled = true;
            verifyBtn.innerText = 'Checking...';

            if (currentWalletType === 'wallet1') {
                // WALLET 1: Check in Downline Only (POST)
                fetch('{{ route("user.search.downline") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ulid: ulidInput,
                        wallet_type: currentWalletType
                    })
                })
                .then(res => res.json())
                .then(data => {
                    verifyBtn.disabled = false;
                    verifyBtn.innerText = 'Verify';

                    if (data.success) {
                        showReceiver(data.user.name, data.user.email, data.user.ulid);
                    } else {
                        alert(data.message || 'User not found in your downline.');
                        hideReceiver();
                    }
                })
                .catch(err => {
                    verifyBtn.disabled = false;
                    verifyBtn.innerText = 'Verify';
                    alert('Something went wrong.');
                });

            } else {
                // WALLET 2: Check Anywhere in System (GET)
                fetch('/get-user-details/' + ulidInput)
                .then(res => res.json())
                .then(data => {
                    verifyBtn.disabled = false;
                    verifyBtn.innerText = 'Verify';

                    if (data.error) {
                        alert(data.error);
                        hideReceiver();
                    } else if (data.ulid) {
                        // For Get User Details API
                        showReceiver(data.name, data.email || 'N/A', data.ulid);
                    } else {
                        alert('User not found.');
                        hideReceiver();
                    }
                })
                .catch(err => {
                    verifyBtn.disabled = false;
                    verifyBtn.innerText = 'Verify';
                    alert('Something went wrong.');
                });
            }
        });

        document.getElementById('mainTransferForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount_input').value);
            const type = document.getElementById('wallet_type_input').value;
            const currentBal = (type === 'wallet1') ? wallet1Bal : wallet2Bal;

            if (isNaN(amount) || amount <= 0) {
                e.preventDefault();
                alert('Please enter a valid amount');
            } else if (amount > currentBal) {
                e.preventDefault();
                alert('Insufficient balance in your ' + (type === 'wallet1' ? 'Main' : 'Second') + ' wallet!');
            } else if (!confirm('Are you sure you want to transfer ₹' + amount + ' to this user?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection