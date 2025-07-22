


// index.js
window.addEventListener("load", calculateAll);

function calculateAll() {
  const tableBody = document.getElementById("data-table-body");
  const rows = tableBody.querySelectorAll("tr");

  const summary = {
    receivedTotal: 0,
    cancelTotal: 0,
    rescheduledTotal: 0,
    totalDelivered: 0,
    totalTk: 0,
    holidayCount: 0,
    absentCount: 0,
    highestDelivered: 0,
    lowestDelivered: Infinity,
    highestDeliveredRow: null,
    lowestDeliveredRow: null,
    serial: 1,
  };

  // Get salary dynamically from hidden input
  const basicSalary = parseInt(document.querySelector(".besic_salary").value);
  const month_day = parseInt(document.querySelector(".month_day").value);
  const oil_cost = parseInt(document.querySelector(".oil_cost").value);

  rows.forEach(row => processRow(row, summary));
  updateSummary(summary, rows.length, basicSalary, month_day, oil_cost);
}

function processRow(row, summary) {
  const cells = row.querySelectorAll("td");
  if (cells.length < 7) return;

  const [
    dateCell,
    receivedCell,
    cancelCell,
    rescheduleCell,
    deliveredCell,
    rateCell,
    totalCell
  ] = cells;

  const receivedText = receivedCell.textContent.trim().toLowerCase();
  const cancel = parseInt(cancelCell.textContent) || 0;
  const reschedule = parseInt(rescheduleCell.textContent) || 0;
  const delivered = parseInt(deliveredCell.textContent) || 0;
  const rate = parseInt(rateCell.textContent) || 0;
  const total = delivered * rate;

  dateCell.textContent = String(summary.serial++).padStart(2, "0");

  summary.receivedTotal += isNaN(receivedCell.textContent) ? 0 : parseInt(receivedCell.textContent) || 0;
  summary.cancelTotal += cancel;
  summary.rescheduledTotal += reschedule;
  summary.totalDelivered += delivered;
  summary.totalTk += total;

  if (delivered > 0) {
    if (delivered > summary.highestDelivered) {
      summary.highestDelivered = delivered;
      summary.highestDeliveredRow = row;
    }
    if (delivered < summary.lowestDelivered) {
      summary.lowestDelivered = delivered;
      summary.lowestDeliveredRow = row;
    }
  }

  applyRowStyling(row, receivedText, delivered, dateCell, summary);
  totalCell.textContent = total.toFixed(0);
}

function applyRowStyling(row, received, delivered, dateCell, summary) {
  switch (received) {
    case "off day":
    case "govt holiday":
      row.style.backgroundColor = "green";
      row.style.color = "white";
      summary.holidayCount++;
      break;
    case "absent":
      row.style.backgroundColor = "red";
      row.style.color = "white";
      summary.absentCount++;
      break;
    case "extra":
      row.style.backgroundColor = "purple";
      row.style.color = "white";
      dateCell.textContent = "#";
      if (delivered === 0) row.style.display = "none";
      break;
    default:
      if (delivered === 40) {
        row.style.backgroundColor = "azure";
      } else if (delivered < 40 && delivered > 0) {
        row.style.backgroundColor = "lightblue";
      } else if (delivered > 40) {
        row.style.backgroundColor = "lightgreen";
      }
  }
}

function updateSummary(summary, totalDays, basicSalary, month_day,oil_cost) {
  const {
    receivedTotal,
    cancelTotal,
    rescheduledTotal,
    totalDelivered,
    totalTk,
    holidayCount,
    absentCount,
    highestDelivered,
    lowestDelivered,
    highestDeliveredRow,
    lowestDeliveredRow
  } = summary;

  const returnTotal = receivedTotal - totalDelivered;
  const returnRate = (returnTotal / receivedTotal) * 100;
  const deliveredRate = 100 - returnRate;

  const possibleWorkDay = month_day - 4 ;
  const workDay = totalDays - holidayCount - absentCount;
  let totalWorkDay = possibleWorkDay - absentCount;
  if (holidayCount > 4) totalWorkDay = totalWorkDay - holidayCount + 4;
  if (workDay > totalWorkDay) totalWorkDay = workDay;

  const avarDel = (totalDelivered / workDay).toFixed(2);
  const avarTk = (totalTk / workDay).toFixed(0);
  const grossSalary = basicSalary - (absentCount * 258);
  const possibleIncome = avarTk * totalWorkDay;
  const possibleTotal = (avarDel * totalWorkDay).toFixed(0);


  let oilBill = 0;
  let possibleOilBill = 0;

  if (document.getElementById("oilBill")) {
    oilBill = workDay * oil_cost;
    updateText("oilBill", oilBill);
  }

  if (document.getElementById("possibleOilBill")) {
    possibleOilBill = totalWorkDay * oil_cost;
    updateText("possibleOilBill", possibleOilBill);
  }

  let salary = 258 * workDay;
  if (totalDays >= 30) {
    salary = basicSalary - (absentCount * 258);
    if (salary < 0) salary = 0;
  }

  const salaryTotal = totalTk + salary + oilBill;
  const estimatedTotal = possibleIncome + grossSalary + possibleOilBill;

  //#region Update Summary Values
      updateText("rcvdTotal", receivedTotal);
      updateText("cancelTotal", cancelTotal);
      updateText("rescheduledTotal", rescheduledTotal);
      updateText("dlvrdTotal", totalDelivered);
      updateText("returnTotal", returnTotal);
      updateText("returnRate", returnRate.toFixed(1));
      updateText("dlvrdRate", deliveredRate.toFixed(1));
      updateText("totalDay", totalDays);
      updateText("offDay", holidayCount);
      updateText("absent", absentCount);
      updateText("totalWrkDay", workDay);
      updateText("possibleTotalWrkDay", totalWorkDay);
      updateText("avarDel", avarDel);
      updateText("highDel", highestDelivered);
      updateText("lowDel", String(lowestDelivered).padStart(2, "0"));
      updateText("avarTk", avarTk);
      updateText("grossSalary", grossSalary);
      updateText("possibleIncome", possibleIncome);
      updateText("estimatedTotal", estimatedTotal);
      updateText("salaryTk", salary);
      updateText("comissiontk", totalTk);
      updateText("salaryTotal", salaryTotal);
      updateText("avarageTotalTk", (salaryTotal / totalDays).toFixed(0));
      updateText("posiTotal", possibleTotal);
  //#endregion


  if (absentCount === 0) {
    const absentRow = document.getElementById("absentRow");
    if (absentRow) absentRow.style.display = "none";
  }

  if (highestDeliveredRow) highestDeliveredRow.style.backgroundColor = "aqua";
  if (lowestDeliveredRow) lowestDeliveredRow.style.backgroundColor = "royalblue";
}


function updateText(id, value) {
  const el = document.getElementById(id);
  if (el) el.innerHTML = value;
}
