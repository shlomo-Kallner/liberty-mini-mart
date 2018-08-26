
export class LaravelAlert {
  constructor (data) {
    let decoded = JSON.parse(data);
    this.data = {
      alertId: LaravelAlert.testVal(decoded.id) ? decoded.id : 'masterPageAlert',
      cssClasses: LaravelAlert.testVal(decoded.class) ? decoded.class : '',
      title: LaravelAlert.testVal(decoded.title) ? decoded.title : '',
      content: LaravelAlert.testVal(decoded.content) ? decoded.content : '',
      timeout: LaravelAlert.testVal(decoded.timeout) ? LaravelAlert.getNumber(decoded.timeout) : '',
      seen: LaravelAlert.testVal(decoded.class)
    };
  }

  getData () {
    return this.data;
  }

  getId () {
    return this.getAlertID();
  }

  setTimeout (timeout) {
    if (typeof timeout === 'number') {
      this.data.timeout = Math.trunc(timeout);
    } else if (typeof timeout === 'string') {
      this.data.timeout = parseInt(timeout);
    } 
  }

  static getNumber (num) {
    if (typeof num === 'number') {
      return Math.trunc(num);
    } else if (typeof num === 'string') {
      return parseInt(num);
    } else {
      return 0;
    }
  }

  static testVal (val) {
    if (val === null) {
      return false;
    } else if (val === '') {
      return false;
    } else if (val === undefined) {
      return false;
    } else if (val === 0) {
      return false;
    } else if (val === 0.0) {
      return false;
    } else {
      return true;
    }
  }

  getTimeout () {
    return this.data.timeout;
  }

  hide() {
    this.data.seen = false;
  }

  show() {
    this.data.seen = true;
  }

  isSeen() {
    return this.data.seen;
  }

  getClass() {
    return this.data.cssClasses;
  }

  getTitle() {
    return this.data.title;
  }

  getAlertID() {
    return this.data.alertId;
  }

  getContent() {
    return this.data.content;
  }
}
