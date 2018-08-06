
export class LaravelAlert {
  constructor (data) {
    let decoded = JSON.parse(data);
    this.data = {
      alertId: decoded.id !== undefined || decoded.id !== '' ? decoded.id : 'masterPageAlert',
      cssClasses: decoded.class !== null || decoded.class !== '' ? decoded.class : '',
      title: decoded.title !== null || decoded.title !== '' ? decoded.title : '',
      content: decoded.content !== null || decoded.content !== '' ? decoded.content : '',
      timeout: decoded.timeout !== null || decoded.timeout !== '' ? LaravelAlert.getNumber(decoded.timeout) : '',
      seen: decoded.class !== null || decoded.class !== ''
    };
  }

  getData () {
    return this.data;
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
      return Math.trunc(timeout);
    } else if (typeof num === 'string') {
      return parseInt(timeout);
    } else {
      return 0;
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
