document.getElementById("myButton").onclick = function () {
  const source = document.getElementById("div1");
  const destination = document.getElementById("div2");
  const sourceList  = source.innerHTML.split('');

  const destinationList = []
  const delayList = initDelayList(sourceList)

  fillDelaysForOdds(sourceList, delayList)
  fillDelaysForEvens(delayList)

  sourceList.forEach((item, index) => {
    setTimeout(() => {
      moveItemToDestinationByIndex(sourceList, destinationList, index);
      source.innerHTML = sourceList.join('');
      destination.innerHTML = destinationList.join('');
    }, delayList[index]);
  }
  );
};

function moveItemToDestinationByIndex(sourceList, destinationList, index) {
  const item = sourceList[index];
  sourceList[index] = null;
  destinationList[index] = item;
}

function initDelayList(sourceList) {
  return Array.from({ length: sourceList.length }, () => 0);
}

function fillDelaysForOdds(sourceList, delayList) {
  const oddBaseDelayMs = 4000;

  for (let index = 0; index < sourceList.length; index++) {
    if(isEven(index)) {
      continue
    }
    delayList[index] = oddBaseDelayMs + (index * 1000);
  }
}

function fillDelaysForEvens(delayList) {
  const count = delayList.length;

  for (let index = 0; index < count; index++) {
    if(!isEven(index)) {
      continue
    }

    const previousIndex = index - 1;
    const nextIndex = index + 1;

    const previousDelay = delayList[previousIndex] || 0;
    const nextDelay = delayList[nextIndex] || 0;
    const biggestDelay = Math.max(previousDelay, nextDelay);
    const delay = biggestDelay + 1000;
    delayList[index] = delay;
  }
}

function isEven(num) {
  return num % 2 === 0;
}