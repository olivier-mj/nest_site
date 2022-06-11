let url = location + 'live/stream';

async function getStream() {
	const response = await fetch(url, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
		mode: 'no-cors'
	});

	const jsonData = await response.json();


	let streamers = jsonData.data;
	let twitchDiv = document.getElementById('twitch');
	if (streamers.length > 0) {
		twitchDiv.classList.add('py-8');
		twitchDiv.classList.add('bg-black');
		twitchDiv.classList.add('h-screen');
		if (streamers.length <= 1) {

			twitchDiv.innerHTML = `<div class="px-4 mx-auto flex ">
			<div id="streamers-list" class="flex   flex-wrap overflow-w-auto item-start space-x-3"></div>
		</div>`;
		} else {
			twitchDiv.innerHTML = `<div class="px-4 mx-auto flex ">
			<div id="streamers-list" class="flex   flex-wrap overflow-w-auto mx-auto space-x-3"></div>
		</div>`;
		}
		let streamersList = document.getElementById('streamers-list');
		streamersList.innerHTML = '';
		for (let i = 0; i < streamers.length; i++) {
			let streamer = streamers[i];
			let streamerElement = document.createElement('div');
			if (streamer.type !== null && streamer.type === 'live') {
				streamerElement.classList.add('streamer');

				// stream.classList.add('flex');
				streamerElement.innerHTML = `
                <div class="relative w-[362px] h-[192px] overflow-hidden rounded">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="absolute inset-0 w-full h-full">
                            <img class="absolute object-cover object-center w-full h-full" src="${streamer.thumbnail_url.replace('{width}', '260').replace('{height}', '160')}" alt="${streamer.user_name}" width="260px" height="160px">
                        </div>
                        <div class="overlay"></div>
                        
                        <div class="relative flex flex-col justify-end w-full h-full p-4 space-y-2 streamer-name">
                            <h2 class=" text-white first-letter:uppercase">${streamer.user_name}</h2>
                            <h3 class="italic font-bold text-white text-shadow-md">${streamer.game_name}</h3>
                            <h4 class="text-sm  truncate text-white">${streamer.title}</h4>
                        </div>
                    </div>
                </div>
                `;
				streamerElement.classList.add('relative');
			}
			streamersList.appendChild(streamerElement);
		}

	}
	console.log(jsonData);
}


setInterval(
	getStream()
, 6000);

























// let twitchDiv = document.getElementById('twitch');
// // let streamersList = document.getElementById('streamers-list');

// fetch(location + "live/stream", {
// 		method: 'GET',
// 		headers: {
// 			'Accept': 'application/json',
// 			'Content-Type': 'application/json',
// 			'Access-Control-Allow-Origin': '*'
// 		},
// 		mode: 'no-cors'
// 	})
// 	.then((response) => response.json())
// 	.then((jsonData) => {
// 		//console.log(jsonData);
// 		let streamers = jsonData.data;

		
// 		if (streamers.length > 0) {
// 			twitchDiv.classList.add('py-8');
// 			twitchDiv.classList.add('bg-black');
// 			twitchDiv.classList.add('h-screen');
// 			if (streamers.length <= 1) {
				
// 				twitchDiv.innerHTML = `<div class="px-4 mx-auto flex ">
// 				<div id="streamers-list" class="flex   flex-wrap overflow-w-auto item-start space-x-3"></div>
// 			</div>`;
// 			} else {
// 				twitchDiv.innerHTML = `<div class="px-4 mx-auto flex ">
// 				<div id="streamers-list" class="flex   flex-wrap overflow-w-auto mx-auto space-x-3"></div>
// 			</div>`;
// 			}

			

	
// 				for (let i = 0; i < streamers.length; i++) {
// 					let streamer = streamers[i];
// 					let streamersList = document.getElementById('streamers-list');
// 					let streamerElement = document.createElement('div');
// 					if (streamer.type !== null && streamer.type === 'live') {
// 						streamerElement.classList.add('streamer');

// 						// stream.classList.add('flex');
// 						streamerElement.innerHTML = `
// 						<div class="relative w-[362px] h-[192px] overflow-hidden rounded">
// 								<div class="absolute inset-0 flex items-center justify-center">
// 									<div class="absolute inset-0 w-full h-full">
// 										<img class="absolute object-cover object-center w-full h-full" src="${streamer.thumbnail_url.replace('{width}', '260').replace('{height}', '160')}" alt="${streamer.user_name}" width="260px" height="160px">
// 									</div>
// 									<div class="overlay"></div>
									
// 									<div class="relative flex flex-col justify-end w-full h-full p-4 space-y-2 streamer-name">
// 										<h2 class=" text-white first-letter:uppercase">${streamer.user_name}</h2>
// 										<h3 class="italic font-bold text-white text-shadow-md">${streamer.game_name}</h3>
// 										<h4 class="text-sm  truncate text-white">${streamer.title}</h4>
// 									</div>
// 								</div>
// 							</div>
// 							`;
// 						streamerElement.classList.add('relative');
// 						// streamerElement.classList.add('p-2');
// 						// streamerElement.classList.add('rounded');
// 						// streamerElement.classList.add('overflow-hidden');
// 						// streamerElement.classList.add('overflow-hidden');
// 						// streamerElement.classList.add('overflow-hidden');
// 						streamersList.appendChild(streamerElement);
// 					}

// 				}
			

// 		}

// 	}).catch((error) => {
// 		console.error(error);
// 	});




//---------------------------------------



