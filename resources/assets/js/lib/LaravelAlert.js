
export class LaravelAlert {
  constructor (data) {
    let decoded = JSON.parse(data);
    this.data = {
      alertId: decoded.id !== undefined || decoded.id !== '' ? decoded.id : 'masterPageAlert',
      cssClasses: decoded.class !== null || decoded.class !== '' ? decoded.class : '',
      title: decoded.title !== null || decoded.title !== '' ? decoded.title : '',
      content: decoded.content !== null || decoded.content !== '' ? decoded.content : '',
      timeout: decoded.timeout !== null || decoded.timeout !== '' ? decoded.timeout : '',
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

  getTimeout () {
    return this.data.timeout;
  }
}
