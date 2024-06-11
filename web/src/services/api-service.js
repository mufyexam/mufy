import HttpRequestService from "./http-request";

const httpRequestService = new HttpRequestService(
  "http://localhost/Mufy/server/"
);

const ApiRoutes = {
  auth: {
    login: "auth/giris-yap.php",
  },
  user: {
    getUser: "user/kullanici-getir.php",
    getUsers: "akademik/kullanici/kullanici-bilgilerini-getir.php",
    addUser: "akademik/kullanici/kullanici-ekle.php",
    deleteUser: "akademik/kullanici/kullanici-sil.php",
    updateUser: "akademik/kullanici/kullanici-guncelle.php",
  },
  panel: {
    statistics: "panel/istatistikleri-getir.php",
  },
  institution: {
    getInstitutions: "fiziksel/kurum/kurum-bilgilerini-getir.php",
    addInstitution: "fiziksel/kurum/kurum-ekle.php",
    deleteInstitution: "fiziksel/kurum/kurum-sil.php",
    updateInstitution: "fiziksel/kurum/kurum-guncelle.php",
  },
  campus: {
    getCampuses: "fiziksel/kampus/kampus-bilgilerini-getir.php",
    addCampus: "fiziksel/kampus/kampus-ekle.php",
    deleteCampus: "fiziksel/kampus/kampus-sil.php",
    updateCampus: "fiziksel/kampus/kampus-guncelle.php",
  },
  building: {
    getBuildings: "fiziksel/bina/bina-bilgilerini-getir.php",
    addBuilding: "fiziksel/bina/bina-ekle.php",
    deleteBuilding: "fiziksel/bina/bina-sil.php",
    updateBuilding: "fiziksel/bina/bina-guncelle.php",
  },
  floors: {
    getFloors: "fiziksel/kat/kat-bilgilerini-getir.php",
    addFloor: "fiziksel/kat/kat-ekle.php",
    deleteFloor: "fiziksel/kat/kat-sil.php",
    updateFloor: "fiziksel/kat/kat-guncelle.php",
  },
  rooms: {
    getRooms: "fiziksel/salon/salon-bilgilerini-getir.php",
    addRoom: "fiziksel/salon/salon-ekle.php",
    deleteRoom: "fiziksel/salon/salon-sil.php",
    updateRoom: "fiziksel/salon/salon-guncelle.php",
  },
  lessons: {
    getLessons: "akademik/ders/ders-bilgilerini-getir.php",
    addLesson: "akademik/ders/ders-ekle.php",
    deleteLesson: "akademik/ders/ders-sil.php",
    updateLesson: "akademik/ders/ders-guncelle.php",
  },
  exams: {
    getExams: "sinav/sinav-bilgilerini-getir.php",
    addExam: "sinav/sinav-olustur.php",
    deleteExam: "sinav/sinav-sil.php",
    updateExam: "sinav/sinav-guncelle.php",
    getExamInformations: "sinav/sinav-getir.php",
    startExam: "sinav/sinav-baslat.php",
    getUserExams: "sinav/kullanici-sinavlarini-getir.php",
  },
};

export default class ApiService {
  static async login(user) {
    return await httpRequestService.post(ApiRoutes.auth.login, user);
  }

  static async getUser(userId) {
    return await httpRequestService.post(ApiRoutes.user.getUser, { userId });
  }

  static async getPanelStatistics(userId) {
    return await httpRequestService.post(ApiRoutes.panel.statistics, {
      userId,
    });
  }

  static async getInstitutions(userId) {
    return await httpRequestService.post(
      ApiRoutes.institution.getInstitutions,
      {
        userId,
      }
    );
  }

  static async addInstitution(userId, institution) {
    return await httpRequestService.post(ApiRoutes.institution.addInstitution, {
      userId,
      ...institution,
    });
  }

  static async updateInstitution(userId, institution) {
    return await httpRequestService.post(
      ApiRoutes.institution.updateInstitution,
      {
        userId,
        ...institution,
      }
    );
  }

  static async deleteInstitutionItem(userId, institutionId) {
    return await httpRequestService.post(
      ApiRoutes.institution.deleteInstitution,
      {
        userId,
        id: institutionId,
      }
    );
  }

  static async getCampuses(userId) {
    return await httpRequestService.post(ApiRoutes.campus.getCampuses, {
      userId,
    });
  }

  static async addCampus(userId, campus) {
    return await httpRequestService.post(ApiRoutes.campus.addCampus, {
      userId,
      ...campus,
    });
  }

  static async deleteCampusItem(userId, campusId) {
    return await httpRequestService.post(ApiRoutes.campus.deleteCampus, {
      userId,
      id: campusId,
    });
  }

  static async updateCampus(userId, campus) {
    return await httpRequestService.post(ApiRoutes.campus.updateCampus, {
      userId,
      ...campus,
    });
  }

  static async getBuildings(userId) {
    return await httpRequestService.post(ApiRoutes.building.getBuildings, {
      userId,
    });
  }

  static async addBuilding(userId, building) {
    return await httpRequestService.post(ApiRoutes.building.addBuilding, {
      userId,
      ...building,
    });
  }

  static async updateBuilding(userId, building) {
    return await httpRequestService.post(ApiRoutes.building.updateBuilding, {
      userId,
      ...building,
    });
  }

  static async deleteBuildingItem(userId, buildingId) {
    return await httpRequestService.post(ApiRoutes.building.deleteBuilding, {
      userId,
      id: buildingId,
    });
  }

  static async getFloors(userId) {
    return await httpRequestService.post(ApiRoutes.floors.getFloors, {
      userId,
    });
  }

  static async addFloor(userId, floor) {
    return await httpRequestService.post(ApiRoutes.floors.addFloor, {
      userId,
      ...floor,
    });
  }

  static async deleteFloorItem(userId, floorId) {
    return await httpRequestService.post(ApiRoutes.floors.deleteFloor, {
      userId,
      id: floorId,
    });
  }

  static async updateFloor(userId, floor) {
    return await httpRequestService.post(ApiRoutes.floors.updateFloor, {
      userId,
      ...floor,
    });
  }

  static async getRooms(userId) {
    return await httpRequestService.post(ApiRoutes.rooms.getRooms, {
      userId,
    });
  }

  static async addRoom(userId, room) {
    return await httpRequestService.post(ApiRoutes.rooms.addRoom, {
      userId,
      ...room,
    });
  }

  static async deleteRoomItem(userId, roomId) {
    return await httpRequestService.post(ApiRoutes.rooms.deleteRoom, {
      userId,
      id: roomId,
    });
  }

  static async updateRoom(userId, room) {
    return await httpRequestService.post(ApiRoutes.rooms.updateRoom, {
      userId,
      ...room,
    });
  }

  static async getLessons(userId) {
    return await httpRequestService.post(ApiRoutes.lessons.getLessons, {
      userId,
    });
  }

  static async addLesson(userId, lesson) {
    return await httpRequestService.post(ApiRoutes.lessons.addLesson, {
      userId,
      ...lesson,
    });
  }

  static async deleteLessonItem(userId, lessonId) {
    return await httpRequestService.post(ApiRoutes.lessons.deleteLesson, {
      userId,
      id: lessonId,
    });
  }

  static async updateLesson(userId, lesson) {
    return await httpRequestService.post(ApiRoutes.lessons.updateLesson, {
      userId,
      ...lesson,
    });
  }

  static async getUsers(userId, role) {
    return await httpRequestService.post(ApiRoutes.user.getUsers, {
      userId,
      role,
    });
  }

  static async addUser(userId, user) {
    return await httpRequestService.post(ApiRoutes.user.addUser, {
      userId,
      ...user,
    });
  }

  static async deleteUserItem(userId, id) {
    return await httpRequestService.post(ApiRoutes.user.deleteUser, {
      userId,
      id,
    });
  }

  static async updateUser(userId, user) {
    return await httpRequestService.post(ApiRoutes.user.updateUser, {
      userId,
      ...user,
    });
  }

  static async getExams(userId) {
    return await httpRequestService.post(ApiRoutes.exams.getExams, {
      userId,
    });
  }

  static async addExam(userId, exam) {
    return await httpRequestService.post(ApiRoutes.exams.addExam, {
      userId,
      ...exam,
    });
  }

  static async updateExam(userId, exam) {
    return await httpRequestService.post(ApiRoutes.exams.updateExam, {
      userId,
      ...exam,
    });
  }

  static async deleteExamItem(userId, examId) {
    return await httpRequestService.post(ApiRoutes.exams.deleteExam, {
      userId,
      id: examId,
    });
  }

  static async getExamInformations(userId, examId) {
    return await httpRequestService.post(ApiRoutes.exams.getExamInformations, {
      userId,
      examId,
    });
  }

  static async startExam(userId, data) {
    return await httpRequestService.post(ApiRoutes.exams.startExam, {
      userId,
      ...data,
    });
  }

  static async getUserExams(userId) {
    return await httpRequestService.post(ApiRoutes.exams.getUserExams, {
      userId,
    });
  }
}
